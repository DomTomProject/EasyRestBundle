<?php

namespace DomTomProject\EasyRestBundle\Utils;

/**
 * Helper class for getting parameters
 */
class Parameter {
    
    private $order;
    
    private $limit;
    
    private $offset;
    
    public function __construct(array $order, int $limit = 10, int $offset = 0){
        $this->order = $order;
        $this->limit = $limit;
        $this->offset = $offset;
    }
    
    public function getOrder(): array {
        return $this->order;
    }
    
    public function getOrderDirection(): string{
        return array_values($this->order)[0];
    }
    
    public function getOrderSubject(): string{
        return array_values(array_flip($this->order))[0];
    }

    public function getLimit(): int {
        return $this->limit;
    }

    public function getOffset(): int {
        return $this->offset;
    }
 
}
