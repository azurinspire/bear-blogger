<?php

namespace AzurInspire\BearBlogger\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;

class MarkdownToHtml implements CastsAttributes
{
    /** @var CommonMarkConverter */
    protected $commonMarkConverter;

    public function __construct()
    {
        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new AttributesExtension());
        $config = [];

        $this->commonMarkConverter = new CommonMarkConverter($config, $environment);
    }

    public function get($model, $key, $value, $attributes)
    {
        return $this->commonMarkConverter->convertToHtml($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}
