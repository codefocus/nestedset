<?php

namespace Codefocus\NestedSet;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 *	NestedSetTrait trait.
 *
 *	Implements a nested set hierarchy in Eloquent models.
 *
 *	Detailed documentation is available at https://github.com/codefocus/nestedset
 */
trait NestedSetTrait
{
    private $defaultColumns = [
        'left' => 'left',
        'right' => 'right',
        'parent' => 'parent_id',
        'depth' => null,
        'group' => null,
    ];

    private $requiredColumns = [
        'left',
        'right',
        'parent',
    ];

    /**
     * Observe the Model.
     */
    public static function bootNestedSetTrait()
    {
        static::observe(NestedSetObserver::class);
    }

    /**
     * Get the name of the "left" column.
     *
     * @return string
     */
    public function getLeftColumn()
    {
        return $this->getColumnName('left');
    }

    /**
     * Get the qualified name of the "left" column.
     *
     * @return string
     */
    public function getQualifiedLeftColumn()
    {
        return $this->getQualifiedColumnName('left');
    }

    /**
     * Get the name of the "right" column.
     *
     * @return string
     */
    public function getRightColumn()
    {
        return $this->getColumnName('right');
    }

    /**
     * Get the qualified name of the "right" column.
     *
     * @return string
     */
    public function getQualifiedRightColumn()
    {
        return $this->getQualifiedColumnName('right');
    }

    /**
     * Get the name of the "parent" column.
     *
     * @return string
     */
    public function getParentColumn()
    {
        return $this->getColumnName('parent');
    }

    /**
     * Get the qualified name of the "parent" column.
     *
     * @return string
     */
    public function getQualifiedParentColumn()
    {
        return $this->getQualifiedColumnName('parent');
    }

    /**
     * Get the name of the "depth" column.
     *
     * @return string
     */
    public function getDepthColumn()
    {
        return $this->getColumnName('depth');
    }

    /**
     * Get the qualified name of the "depth" column.
     *
     * @return string
     */
    public function getQualifiedDepthColumn()
    {
        return $this->getQualifiedColumnName('depth');
    }

    /**
     * Get the name of the "group" column.
     *
     * @return string
     */
    public function getGroupColumn()
    {
        return $this->getColumnName('group');
    }

    /**
     * Get the qualified name of the "group" column.
     *
     * @return string
     */
    public function getQualifiedGroupColumn()
    {
        return $this->getQualifiedColumnName('group');
    }

    /**
     * Get the name of one of the configurable columns.
     *
     * @return string
     */
    private function getColumnName($column)
    {
        if (isset($this->nestedSetColumns[$column])) {
            if (empty($this->nestedSetColumns[$column]) and in_array($column, $this->requiredColumns)) {
                //	Throw an exception if a required column is empty.
                throw new \UnexpectedValueException('"' . $column . '" column cannot be empty in ' . get_class($model));
            }

            return $this->nestedSetColumns[$column];
        }

        return $this->defaultColumns[$column];
    }

    /**
     * Get the qualified name of one of the configurable columns.
     *
     * @return string
     */
    private function getQualifiedColumnName($column)
    {
        $columnName = $this->getColumnName($column);
        if (empty($columnName)) {
            return;
        }

        return $this->getTable() . '.' . $columnName;
    }

    /**
     * Ensure depth is available in the query result,
     * whether through the depth column or by calculating.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function scopeWithDepth(Builder $builder)
    {
        $depthColumn = $this->getDepthColumn();
        if (is_null($depthColumn)) {
            return $this->scopeWithCalculatedDepth($builder);
        }

        return $builder;
    }

    /**
     * Add a calculated depth value to each query result,
     * if the depth column is not configured.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function scopeWithCalculatedDepth(Builder $builder)
    {
        $tableName = $this->getTable();
        $query = $builder->getQuery();
        $grammar = $query->getGrammar();
        //	Build subquery
        $subquery = \DB::table($tableName . ' AS ns_d')
            ->selectRaw('COUNT(ns_d.left)')
            ->whereRaw($grammar->wrap($tableName) . '.left BETWEEN ns_d.left AND ns_d.right')
            ->toSql();

        $depthColumn = '(' . $subquery . ') AS depth';
        //	Get requested columns and add "*" if none are specified yet,
        //	so we don't just select our subquery.
        $query = $builder->getQuery();
        if (is_null($query->columns)) {
            $query->columns = ['*'];
        }
        //	Add depth subquery as a column
        return $query->selectRaw($depthColumn);
    }

    public function scopeLimitDepth(Builder $query, $maximumDepth, $minimumDepth = 0)
    {
    }

    /**
     * scopeForGroup function.
     *
     * @param Builder $query
     * @param mixed   $groupId
     *
     * @return Builder
     */
    public function scopeForGroup(Builder $query, $groupId)
    {
        $groupColumn = $this->getGroupColumn();
        if (is_null($groupColumn)) {
            //	Depth specified but no depth column configured
            throw new \UnexpectedValueException('Depth specified, but no depth column configured.');
        }
        echo $groupId;
        $query = $query->where($groupColumn, '=', $groupId);
    }

    /**
     * Build the complete tree structure into existing data.
     * This is a slow recursive process and should be used with caution.
     *
     * @static
     */
    public static function buildNewTree()
    {
    }

    /**
     * (re)build the tree structure below the current node.
     * This is a slow recursive process and should be used with caution.
     *
     * @static
     */
    public function buildTree($left = 0)
    {
    }

    /**
     * buildTree function.
     *
     * @param int $left (default: 0)
     */
    public function OLD_buildTree($left = 0)
    {
        //	the right value of this Model is the left value + 1
        $right = $left + 1;
        //	get all children of this Model
        $children = $this->children;

        //	Recursively build the tree below this Model.
        foreach ($children as $child) {
            $right = $child->buildTree($right);
        }

        //	We have the left value, and now that we have
        //	processed this Model's children, we also know
        //	the right value.
        try {
            $this->left = $left;
            $this->right = $right;
            $this->save();
        } catch (\Exception $e) {
            //	Ignore an exception here.
            //	The "left" and "right" fields are not yet present in the database.
            unset($this->left, $this->right);
        }

        //	Return the right value of this Model + 1
        return ++$right;
    }

    //	function rebuildTree

    /**
     * Get the model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this;
    }

    /**
     * Relationship to parent.
     *
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(get_class($this), $this->getParentColumn());
    }

    /**
     * Relationship to children.
     *
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany(get_class($this), $this->getParentColumn());
    }

    /**
     * descendants function.
     *
     * @param int $depth (default: null)
     *
     * @return Builder
     */
    public function descendants($depth = null)
    {
        $leftColumn = $this->getLeftColumn();
        $rightColumn = $this->getRightColumn();
        $groupColumn = $this->getGroupColumn();

        $query = $this->whereBetween($leftColumn, [$this->{$leftColumn} + 1, $this->{$rightColumn} - 1]);
        if (!is_null($groupColumn)) {
            $query = $query->forGroup($this->{$groupColumn});
        }

        if ($depth) {
            $depthColumn = $this->getDepthColumn();
            if (is_null($depthColumn)) {
                //	Depth specified but no depth column configured
                throw new \UnexpectedValueException('Depth specified, but no depth column configured.');
            }
            //	Limit depth
            $query = $query->where($depthColumn, '<=', max($this->{$depthColumn} + $depth));
        }

        return $query;
    }

    /**
     * Descendants attribute.
     * attribute alias for the descendants() function.
     * This returns the query results instead of the Builder.
     *
     * @return Collection
     */
    public function getDescendantsAttribute()
    {
        return $this->descendants()->get();
    }

    /**
     * ancestors function.
     *
     * @param int $depth (default: null)
     *
     * @return Builder
     */
    public function ancestors($depth = null)
    {
        $leftColumn = $this->getLeftColumn();
        $rightColumn = $this->getRightColumn();
        $groupColumn = $this->getGroupColumn();

        $query = $this
            ->where($leftColumn, '<', $this->{$leftColumn})
            ->where($rightColumn, '>', $this->{$rightColumn});

        if (!is_null($groupColumn)) {
            $query = $query->forGroup($this->{$groupColumn});
        }

        if ($depth) {
            $depthColumn = $this->getDepthColumn();
            if (is_null($depthColumn)) {
                //	Depth specified but no depth column configured
                throw new \UnexpectedValueException('Depth specified, but no depth column configured.');
            }
            //	Limit depth, only if the depth specified does not take us to the root level.
            $minDepth = max(0, $this->{$depthColumn} - $depth);
            if ($minDepth > 0) {
                $query = $query->where($depthColumn, '>=', $minDepth);
            }
        }

        return $query;
    }

    /**
     * Ancestors attribute.
     * attribute alias for the ancestors() function.
     * This returns the query results instead of the Builder.
     *
     * @return Collection
     */
    public function getAncestorsAttribute()
    {
        return $this->ancestors()->get();
    }

    /**
     * descendantCount attribute.
     * Returns the number of descendants for this node.
     *
     * @return int
     */
    public function getDescendantCountAttribute()
    {
        $leftColumn = $this->getLeftColumn();
        $rightColumn = $this->getRightColumn();

        return floor(($this->{$rightColumn} - $this->{$leftColumn} - 1) / 2);
    }

    public function scopeTree(Builder $query, $depth = null)
    {
        $parentColumn = $this->getParentColumn();
        $leftColumn = $this->getQualifiedLeftColumn();

        if ($depth) {
            //	If depth is specified, ensure it's included in the query.
            //	If a depth field is not configured, it is calculated.
            $query->withDepth();
            $query->limitDepth($depth);
        }

        $query->orderBy($leftColumn);

        //dd($query->toSql());

        /*
            //	If we're using parent_id, use that to get the toplevel nodes.
                if (!is_null($parentColumn)) {
                    $query->whereNull($parentColumn);
                }

            //	If we're using depth, use that to get the toplevel nodes.
                if (!is_null($depthColumn)) {
                    return $query->where($depthColumn, '=', 0);
                }
        */

        return $query;

        //	Get ALL nodes and order by left.

        /*
                if (!is_null($depthColumn)) {
                    return static::where($depthColumn, '=', 0);
                }
        */

        return static::whereBetween($leftColumn, [$this->{$leftColumn} + 1, $this->{$rightColumn} - 1]);

        dd('Building tree');
    }
}
