<?php

use PHPUnit\Framework\TestCase;
use seivad\sanitizer\Sanitizer;
use seivad\sanitizer\Laravel\Factory;

class FactoryTest extends TestCase
{
    /**
     * @param $data
     * @param $rules
     * @return mixed
     */
    public function sanitize($data, $rules)
    {
        $sanitizer = new Sanitizer($data, $rules, [
            'capitalize' => \Seivad\Sanitizer\Filters\Capitalize::class,
            'escape' => \Seivad\Sanitizer\Filters\EscapeHTML::class,
            'format_date' => \Seivad\Sanitizer\Filters\FormatDate::class,
            'lowercase' => \Seivad\Sanitizer\Filters\Lowercase::class,
            'uppercase' => \Seivad\Sanitizer\Filters\Uppercase::class,
            'trim' => \Seivad\Sanitizer\Filters\Trim::class,
        ]);

        return $sanitizer->sanitize();
    }

    public function test_custom_closure_filter()
    {
        $factory = new Factory;
        $factory->extend('hash', function ($value) {
            return sha1($value);
        });

        $data = [
            'name' => 'TEST',
        ];
        $rules = [
            'name' => 'hash',
        ];

        $newData = $factory->make($data, $rules)->sanitize();
        $this->assertEquals(sha1('TEST'), $newData['name']);
    }

    public function test_custom_class_filter()
    {
        $factory = new Factory;
        $factory->extend('custom', CustomFilter::class);

        $data = [
            'name' => 'TEST',
        ];
        $rules = [
            'name' => 'custom',
        ];

        $newData = $factory->make($data, $rules)->sanitize();
        $this->assertEquals('TESTTEST', $newData['name']);
    }

    public function test_replace_filter()
    {
        $factory = new Factory;
        $factory->extend('trim', function ($value) {
            return sha1($value);
        });

        $data = [
            'name' => 'TEST',
        ];
        $rules = [
            'name' => 'trim',
        ];

        $newData = $factory->make($data, $rules)->sanitize();
        $this->assertEquals(sha1('TEST'), $newData['name']);
    }
}
