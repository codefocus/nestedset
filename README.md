# NestedSet

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

Simple to use implementation of the nested set structure, for Eloquent models in Laravel.

## Table of contents

* [Install](#install)
* [Configuration](#configuration)
  * [Enabling the nested set functionality in your model](#enabling-the-nested-set-functionality-in-your-model)
  * [Database columns](#database-columns)
  * [Database indexes](#database-indexes)
* [Usage](#usage)
  * [Building a tree](#building-a-tree)
    * [Building a new tree from an existing parent-child based data structure](#building-a-new-tree-from-an-existing-parent-child-based-data-structure)
    * [Adding a node to a tree](#adding-a-node-to-a-tree)
    * [Moving a node](#moving-a-node)
    * [Removing a node from a tree](#removing-a-node-from-a-tree)
* [Change log](#change-log)
* [Testing](#testing)
* [Contributing](#contributing)
* [Security](#security)
* [Credits](#credits)

## Install

Via Composer

``` bash
$ composer require codefocus/nestedset
```

## Configuration

### Enabling the nested set functionality in your model

To implement the NestedSetTrait, simply `use` it in your model:

``` php
class YourModel extends \Illuminate\Database\Eloquent\Model {
    
    use \Codefocus\NestedSet\NestedSetTrait;
    ...
    
}
```

### Database columns

The Trait expects database columns to be present for (at least) your Model's `left`, `right` and `parent_id` fields.
The names of these fields can be configured per Model,
by setting the following protected variables in the Model that uses it:
 
``` php
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

### Database indexes

Indexes are highly recommended on these fields (or the ones configured in `$nestedSetColumns`):

- `left`, `right`, `group`, `depth`
- `left`, `group`, `depth`
- `parent_id`

If you are not using `depth` and `group`, these indexes will suffice:

- `left`, `right`
- `parent_id`


## Usage


### Building a tree

#### Building a new tree from an existing parent-child based data structure

Use your data's existing parent &rarr; child hierarchy to construct a new tree
(or multiple trees, if you have configured the `$nestedSetColumns['group']` column in your model).

This may take a while, depending on the size of your data set!

``` php
YourModel::buildNewTree();
```

#### Adding a node to a tree

Adding a node to the tree requires literally no work.
Just save a model instance as usual, and the Trait will automagically adjust the tree structure.

``` php
$yourModelInstance->save();
```

#### Moving a node

Moving a node from one parent to another (or no parent) is handled in the same way.
When the Trait sees that a model instance's `parent_id` (or the column name configured in `$nestedSetColumns['parent']`) value has changed, the tree structure is adjusted accordingly.

``` php
$yourModelInstance->parent_id = $newParent->id;
$yourModelInstance->save();
```

#### Removing a node from a tree

Deleting a node from the tree is also automated by the Trait.
When you delete a model instance as usual, the Trait will adjust the tree structure.

``` php
$yourModelInstance->delete();
```





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
