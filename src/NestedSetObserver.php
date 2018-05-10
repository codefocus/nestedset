<?php

namespace Codefocus\NestedSet;

use Illuminate\Database\Eloquent\Model;

/**
 * NestedSetObserver class.
 *
 * Hook into events of Models that use the NestedSetTrait.
 * Available events: creating, created, updating, updated, saving, saved, deleting, deleted, restoring, restored
 */
class NestedSetObserver
{
    public function creating(Model $model)
    {
        //	Get column names.
        //	Missing required columns will throw an exception.
        $pkColumn = $model->getKeyName();
        $leftColumn = $model->getLeftColumn();
        $rightColumn = $model->getRightColumn();
        $parentColumn = $model->getParentColumn();
        $depthColumn = $model->getDepthColumn();
        $groupColumn = $model->getGroupColumn();

        if (empty($model->{$parentColumn})) {
            //	No parent referenced.
            //	We're creating a node at the root level of the tree.
            if (!is_null($groupColumn)) {
                //	Group column is configured.
                //	Get the max "right" value in the group to which this node belongs.
                $currentMaxRight = $model::where($groupColumn, '=', $model->{$groupColumn})
                    ->max($rightColumn);
            } else {
                //	Group column is not configured.
                //	Get the max "right" value across the whole table.
                $currentMaxRight = $model::max($rightColumn);
            }
            //	Ensure we have a positive number.
            $currentMaxRight = max(0, $currentMaxRight);

            //	Define the node's new "left" and "right".
            $model->{$leftColumn} = $currentMaxRight + 1;
            $model->{$rightColumn} = $currentMaxRight + 2;

            //	If a depth column is configured, set that to 0,
            //	indicating we're at the root level.
            if (!empty($depthColumn)) {
                $model->{$depthColumn} = 0;
            }
        } else {
            //	A parent node is referenced by this node.
            //	We're creating a child.

            //	Get the parent and its "left" and "right" values.
            $parent = $model::findOrFail(
                $model->{$parentColumn},
                array_filter([
                    $pkColumn,
                    $leftColumn,
                    $rightColumn,
                    $depthColumn,
                    $groupColumn,
                ])
            );

            //	Define the node's new "left" and "right".
            //	We're inserting ourselves at the rightmost side of our parent container.
            $model->{$leftColumn} = $parent->right;
            $model->{$rightColumn} = $parent->right + 1;

            //	Make space.
            if (!is_null($groupColumn)) {
                //	Group column is configured.
                //	Only move nodes in the same group as our parent.
                $model::where($groupColumn, '=', $model->{$groupColumn})
                    ->where($rightColumn, '>=', $parent->right)
                    ->increment($rightColumn, 2);
                $model::where($groupColumn, '=', $model->{$groupColumn})
                    ->where($leftColumn, '>=', $parent->right)
                    ->increment($leftColumn, 2);
            } else {
                //	Group column is not configured.
                //	Move nodes across the whole table.
                $model::where($rightColumn, '>=', $parent->right)
                    ->increment($rightColumn, 2);
                $model::where($leftColumn, '>=', $parent->right)
                    ->increment($leftColumn, 2);
            }

            if (!is_null($groupColumn)) {
                //	Group column is configured.
                //	Ensure we live in the same group as our parent.
                $model->{$groupColumn} = $parent->{$groupColumn};
            }

            if (!empty($depthColumn)) {
                //	Depth column is configured.
                //	Set our depth one level deeper than our parent.
                $model->{$depthColumn} = $parent->{$depthColumn} + 1;
            }
        }
    }

    public function updating(Model $model)
    {
        //	An node is being updated.
        //	Rebuild the tree if the parent was changed.

        //	Get column names.
        //	Missing required columns will throw an exception.
        $pkColumn = $model->getKeyName();
        $leftColumn = $model->getLeftColumn();
        $rightColumn = $model->getRightColumn();
        $parentColumn = $model->getParentColumn();
        $depthColumn = $model->getDepthColumn();
        $groupColumn = $model->getGroupColumn();

        $originalParentId = $model->getOriginal($parentColumn);
        if ($originalParentId !== $model->{$parentColumn}) {
            $model->syncOriginalAttribute($parentColumn);

            echo 'parent changed from "' . $originalParentId . '" to "' . $model->{$parentColumn} . '"';

            if (!is_null($originalParentId)) {
                //	Load original parent
                $originalParent = $model::findOrFail(
                    $originalParentId,
                    array_filter([
                        $pkColumn,
                        $leftColumn,
                        $rightColumn,
                        $depthColumn,
                        $groupColumn,
                    ])
                );
            }
            if (!is_null($model->{$parentColumn})) {
                //	Load new parent
                $newParent = $model::findOrFail(
                    $model->{$parentColumn},
                    array_filter([
                        $pkColumn,
                        $leftColumn,
                        $rightColumn,
                        $depthColumn,
                        $groupColumn,
                    ])
                );
                if ($newParent->{$leftColumn} > $model->{$leftColumn} and $newParent->{$leftColumn} < $model->{$rightColumn}) {
                    //	This change would create a circular tree.
                    throw new \Exception('Changing this node\'s parent to ' . $model->{$parentColumn} . ' creates a circular reference');
                }
            }

            //$originalParent = $model->where

            //	@TODO:	Move this to the Trait. Override save().

            dd($modelId);

            /*
            SELECT id FROM node
            WHERE node.left BETWEEN 1 AND 10
            AND node.id = 8
            */

            //	Rebuild the entire tree.
            //	@TODO:	Be smarter about this method.
            dd($model);

            $rootNode = $model::where($parentColumn, '=', null)->first();
            $rootNode->buildTree();
        }

        return true;
    }

    public function deleting(Model $model)
    {

    //	Get column names.
        //	Missing required columns will throw an exception.
        $pkColumn = $model->getKeyName();
        $leftColumn = $model->getLeftColumn();
        $rightColumn = $model->getRightColumn();
        $parentColumn = $model->getParentColumn();

        //	A node is being deleted.
        //	Rebuild the entire tree.
        echo 'node deleted';
        //	@TODO:	Be smarter about this method.
        dd($model);
        $rootNode = $model::where($parentColumn, '=', null)->first();
        $rootNode->buildTree();

        return true;
    }
}	//	NestedSetObserver
