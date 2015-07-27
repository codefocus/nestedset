# NestedSet

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

Simple to use implementation of the nested set structure, for Eloquent models in Laravel.


## Install

Via Composer

``` bash
$ composer require codefocus/nestedset
```

## Configuration

To implement the NestedSetTrait, simply `use` it in your model:

``` php
class YourModel extends \Illuminate\Database\Eloquent\Model {
    
    use \Codefocus\NestedSet\NestedSetTrait;
    ...
    
}
```

The Trait expects database columns to be present for your Model's `left`, `right` and `parent_id` fields.

The names of these fields can be adjusted per Model, by setting the following protected variables in the Model that uses it:
 
```
    protected $nestedSetColumns = [
    //  Which column to use for the "left" value.
    //	Default: left
        'left' => 'left',
        
    //  Which column to use for the "right" value.
    //	Default: right
        'right' => 'right',
        
    //  Which column to point to the parent's PK.
    //  Null is allowed. This will remove the ability to rebuild the tree.
    //	Default: parent_id
        'parent' => 'parent_id',
        
    //  Which column to use for the node's "depth", or level in the tree.
    //  Null is allowed.
    //    ! When restricting the tree by depth, each node's depth will be
    //      calculated automatically. This is not recommended for large trees.
    //	Default: null
        'depth' => null,
        
    //  When a table can hold multiple trees, we need to specify which field
    //  uniquely identifies which tree we are operating on.
    //  E.g. in the case of comments, that could be "thread_id" or "post_id".
    //  Null is allowed. NestedSetTrait will assume there is only one tree.
    //	Default: null
        'group' => null,
    ];
```


## Usage


### Building a tree

#### Building a tree from an existing parent-child based data structure

@TODO: Documentation

#### Adding a node to a tree

@TODO: Documentation

#### Removing a node from a tree

@TODO: Documentation

#### Moving a node

@TODO: Documentation



@TODO: http://www.codefocus.ca/blog/efficient-tree-retrieval-in-laravel-using-the-nested-set


## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email info@codefocus.ca instead of using the issue tracker.

## Credits

- [Menno van Ens][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/codefocus/nestedset.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/codefocus/nestedset/master.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/codefocus/nestedset.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/codefocus/nestedset
[link-travis]: https://travis-ci.org/codefocus/nestedset
[link-downloads]: https://packagist.org/packages/codefocus/nestedset
[link-author]: https://github.com/codefocus
[link-contributors]: ../../contributors
