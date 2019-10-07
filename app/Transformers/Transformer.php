<?php

        namespace App\Transformers;

        abstract class Transformer
        {
            public function transformCollection(array $item)
            {
                return array_map([$this,'transform'],$item);
            }

            abstract public function transform($item);
        }
