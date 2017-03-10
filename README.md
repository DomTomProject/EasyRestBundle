# EasyRestBundle
**It's bundle for fast and clean validating and setting data. Bundle use [Respect/Valiadtion](https://github.com/Respect/Validation) library and there are all validation rules.**

## Basic use

Installation
```
    composer require domtomproject/easy-rest-bundle "dev-master"
```    
Or in composer.json
```
   "domtomproject/easy-rest-bundle": "dev-master"
```
And 
```
    composer update
```

In AppKernel.php
```
    $bundles = [ 
      ...
      new DomTomProject\EasyRestBundle\DomtomEasyRestBundle(),
      ...
    ]
```

In config.yml
```
    domtom_easy_rest: ~
```


For first you need to create rules file. Default validation files path is *app/Resources/validation*. For this example we create User.yml
```
default: # its a key 
    name: 
        - notEmpty
        - stringType
    age:
        - notEmpty
        - intType
    sex:
        - notEmpty
        - in: [[male, female]] 
    language_with_skill:
        - keySet:
            - $key: 
                - pl
                - $in: [[intermediate, basic, none, national ]]
            - $key: 
                - en 
                - $in: [[intermediate, basic, none, national ]]
```
Look at $ symbol . If -keySet argument is a function you must use $ symbol otherwise you pass assoc array with key 'key' to this.
Starting function have not $ symbol.
This yaml file is equivalent with:

```
/* static version */
use Respect\Validation\Validator as v; return array (
  'default' => 
  array (
    'name' => v::notEmpty()->stringType(),
    'age' => v::notEmpty()->intType(),
    'sex' => v::notEmpty()->in(["male", "female"]),
    'language_with_skill' => v::keySet(v::key("pl", v::in(["intermediate", "basic", "none", "national"])), v::key("en", v::in(["intermediate", "basic", "none", "national"]))),
  ),
);

/* 'new' version, used for caching (because it's little bit faster) */
use Respect\Validation\Rules; return array (
  'default' => 
  array (
    'name' => new Rules\AllOf(new Rules\NotEmpty(), new Rules\StringType()),
    'age' => new Rules\AllOf(new Rules\NotEmpty(), new Rules\IntType()),
    'sex' => new Rules\AllOf(new Rules\NotEmpty(), new Rules\In(["male", "female"])),
    'language_with_skill' => new Rules\AllOf(new Rules\KeySet(new Rules\Key("pl", new Rules\In(["intermediate", "basic", "none", "national"])), new Rules\Key("en", new Rules\In(["intermediate", "basic", "none", "national"])))),
  ),
);
```
This file is cache created from yml.

Now you can use this rules.
```
  use AppBundle\Entity\User;
  
  ...

  $validator = $this->get('domtom_easy_rest.validation');
  $rules = $this->get('domtom_easy_rest.rules')->getDefault(User::class); 
  /* can be string like 'User', getDefault(User::class) equals get(User::class, 'default'), 
  where 'default' is a key in yml 
  */
  
  if(!$validator->validate($data, $rules)){
            return new JsonResponse($validator->getErrors());
  }
  
  $validData = $validator->getValidData();
 
  // now if you use FillableEntityTrait in your entity you can pass this valid data to setFromData method
 $user->setFromData($validData);

  
```

To test this we send request with JSON.
```
{
  "name": "John",
  "sex": "male",
  "language_with_skill": {
    "pl": "intermediate",
    "en": "national"
  },
  "age": 30
}
```

In this example everything going to work. 

Configurable data is:
```
    domtom_easy_rest: 
        rules_directory: %kernel.root_dir%/Resources/Validation # if you want change validation files directory
        rules_parser_service: domtom_easy_rest.yaml_rules_parser # if you want change parser, thats service name. Builded are 
                                                                 # yaml_rules_parser and php_rules_parser 
        cacher_service: domtom_easy_rest.cacher # like in rules_parser_service
        serializer_service: jms_serializer # bundle uses jms_serializer ,but you can use serializer you want
```

For rules customization check [Respect/Valiadtion](https://github.com/Respect/Validation)

