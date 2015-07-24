<?php namespace Codefocus\NestedSet;

use App;
use Illuminate\Database\Eloquent\Model;

/**
 *	NestedSetTrait trait.
 *
 *	Implements a nested set hierarchy.
 *	Assumes the Model's database table contains the fields:
 *	- left
 *	- right
 *	- parent_id (or "parent_***", depending on the Model's PK)
 *
 */
trait NestedSetTrait {
	
	private $defaultColumns = [
		'left'		=> 'left',
		'right'		=> 'right',
		'parent'	=> 'parent_id',
		'depth'		=> null,
		'group'		=> null,
	];
	
	private $requiredColumns = [
		'left',
		'right',
		'parent',
	];
	
	
	/**
	 * Observe the Model.
	 *
	 * @return void
	 */
	public static function bootNestedSetTrait() {
		 //static::addGlobalScope(new NestedSetScope);
		static::observe(new NestedSetObserver);
		
	}
	
	
	
	
	
	/**
	 * Get the name of the "left" column.
	 *
	 * @return string
	 */
	public function getLeftColumn() {
		return $this->getColumnName('left');
	}
	
	/**
	 * Get the qualified name of the "left" column.
	 *
	 * @return string
	 */
	public function getQualifiedLeftColumn() {
		return $this->getQualifiedColumnName('left');
	}
	
	/**
	 * Get the name of the "right" column.
	 *
	 * @return string
	 */
	public function getRightColumn() {
		return $this->getColumnName('right');
	}
	
	/**
	 * Get the qualified name of the "right" column.
	 *
	 * @return string
	 */
	public function getQualifiedRightColumn() {
		return $this->getQualifiedColumnName('right');
	}
	
	/**
	 * Get the name of the "parent" column.
	 *
	 * @return string
	 */
	public function getParentColumn() {
		return $this->getColumnName('parent');
	}
	
	/**
	 * Get the qualified name of the "parent" column.
	 *
	 * @return string
	 */
	public function getQualifiedParentColumn() {
		return $this->getQualifiedColumnName('parent');
	}
	
	/**
	 * Get the name of the "depth" column.
	 *
	 * @return string
	 */
	public function getDepthColumn() {
		return $this->getColumnName('depth');
	}
	
	/**
	 * Get the qualified name of the "depth" column.
	 *
	 * @return string
	 */
	public function getQualifiedDepthColumn() {
		return $this->getQualifiedColumnName('depth');
	}
	
	/**
	 * Get the name of the "group" column.
	 *
	 * @return string
	 */
	public function getGroupColumn() {
		return $this->getColumnName('group');
	}
	
	/**
	 * Get the qualified name of the "group" column.
	 *
	 * @return string
	 */
	public function getQualifiedGroupColumn() {
		return $this->getQualifiedColumnName('group');
	}
	
	/**
	 * Get the name of one of the configurable columns.
	 *
	 * @return string
	 */
	private function getColumnName($column) {
		if (isset($this->nestedSetColumns[$column])) {
			if (empty($this->nestedSetColumns[$column]) and in_array($column, $this->requiredColumns)) {
			//	Throw an exception if a required column is empty.
				throw new \UnexpectedValueException('"'.$column.'" column cannot be empty in '.get_class($model));
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
	private function getQualifiedColumnName($column) {
		$columnName = $this->getColumnName($column);
		if (empty($columnName)) {
			return null;
		}
		return $this->getTable().'.'.$columnName;
	}
	
	
	
	
	/**
	 * buildTree function.
	 * 
	 * @access public
	 * @param int $left (default: 0)
	 * @return void
	 */
	public function buildTree($left = 0) {
	//	the right value of this Model is the left value + 1
		$right = $left + 1;
	//	get all children of this Model
		$children = $this->children;
		
	//	Recursively build the tree below this Model.
		foreach($children as $child) {
			$right = $child->buildTree($right);
		}
		
	//	We have the left value, and now that we have
	//	processed this Model's children, we also know
	//	the right value.
		try {
			$this->left = $left;
			$this->right = $right;
			$this->save();
		}
		catch(\Exception $e) {
		//	Ignore an exception here.
		//	The "left" and "right" fields are not yet present in the database.
			unset($this->left);
			unset($this->right);
		}
		
	//	Return the right value of this Model + 1
		return ++$right;
	}	//	function rebuildTree
	
	
	/**
	 * Get the model.
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function getModel() {
		return $this;
	}
	
	/**
	 * Relationship to parent.
	 *
	 * @return BelongsTo
	 */
	public function parent() {
		return $this->belongsTo(get_class($this), $this->getParentColumn());
	}
	
	/**
	 * Relationship to children.
	 *
	 * @return HasMany
	 */
	public function children() {
		return $this->hasMany(get_class($this), $this->getParentColumn());
	}
	
	
	/**
	 * descendants function.
	 * 
	 * @access public
	 * @param int $depth (default: null)
	 * @return Builder
	 */
	public function descendants($depth = null) {
		$leftColumn = $this->getLeftColumn();
		$rightColumn = $this->getRightColumn();
		$groupColumn = $this->getGroupColumn();
		
		$query = $this->whereBetween($leftColumn, [$this->$leftColumn + 1, $this->$rightColumn - 1]);
		
		if (!is_null($groupColumn)) {
			$query = $query->where($groupColumn, '=', $this->$groupColumn);
		}
		
		if ($depth) {
			$depthColumn = $this->getDepthColumn();
			if (is_null($depthColumn)) {
			//	Depth specified but no depth column configured
				throw new \UnexpectedValueException('Depth specified, but no depth column configured.');
			}
		//	Limit depth
			$minDepth = max(0, $this->$depthColumn - $depth);
			if ($minDepth > 0) {
				$query = $query->where($depthColumn, '>=', max(0, $this->$depthColumn - $depth));
			}
		}
		return $query;
	}
	
	
	/**
	 * Descendants attribute.
	 * attribute alias for the descendants() function.
	 * This returns the query results instead of the Builder.
	 * 
	 * @access public
	 * @return Collection
	 */
	public function getDescendantsAttribute() {
		return $this->descendants()->get();
	}
	
	
	/**
	 * ancestors function.
	 * 
	 * @access public
	 * @param int $depth (default: null)
	 * @return Builder
	 */
	public function ancestors($depth = null) {
		$leftColumn = $this->getLeftColumn();
		$rightColumn = $this->getRightColumn();
		$groupColumn = $this->getGroupColumn();
		
		$query = $this->where($leftColumn, '<', $this->$leftColumn)
			->where($rightColumn, '>', $this->$rightColumn);
		
		
		if (!is_null($groupColumn)) {
			$query = $query->where($groupColumn, '=', $this->$groupColumn);
		}
		
		if ($depth) {
			$depthColumn = $this->getDepthColumn();
			if (is_null($depthColumn)) {
			//	Depth specified but no depth column configured
				throw new \UnexpectedValueException('Depth specified, but no depth column configured.');
			}
		//	Limit depth
			$minDepth = max(0, $this->$depthColumn - $depth);
			if ($minDepth > 0) {
				$query = $query->where($depthColumn, '>=', max(0, $this->$depthColumn - $depth));
			}
		}
		return $query;
	}
	
	/**
	 * Ancestors attribute.
	 * attribute alias for the ancestors() function.
	 * This returns the query results instead of the Builder.
	 * 
	 * @access public
	 * @return Collection
	 */
	public function getAncestorsAttribute() {
		return $this->ancestors()->get();
	}
	
	
	
}
