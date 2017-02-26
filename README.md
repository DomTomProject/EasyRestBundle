# EasyRestBundle
**It's bundle for fast and clean validating and setting data. Bundle use [Respect/Valiadtion](https://github.com/Respect/Validation) library and there are all validation rules.**

## For now bundle not working perfectly, it's aplha version

## Basic use

Installation

    composer composer require domtomproject/easy-rest-bundle "dev-master"
    
For first you need to create rules file. Default validation files path is *app/Resources/validation*. For this example we create User.yml
```
default:
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
use Respect\Validation\Validator as v; return array (
  'default' => 
  array (
    'name' => v::notEmpty()->stringType(),
    'age' => v::notEmpty()->intType(),
    'sex' => v::notEmpty()->in(["male", "female"]),
    'language_with_skill' => v::keySet(v::key("pl", v::in(["intermediate", "basic", "none", "national"])), v::key("en", v::in(["intermediate", "basic", "none", "national"]))),
  ),
);
```
This file is cache created from yml.

Now you can use this rules.
```
  use AppBundle\Entity\User;
  
  ...

  $validator = $this->get('domtom_easy_rest.validation');
  $rules = $this->get('domtom_easy_rest.rules')->getDefault(User::class); // can be string like 'User'
  
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

In this example everything going to work. Errors are returned as array.

