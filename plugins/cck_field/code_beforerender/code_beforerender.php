<?php
/**
* @version 			SEBLOD 3.x More
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2009 - 2016 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

defined( '_JEXEC' ) or die;

// Plugin
class plgCCK_FieldCode_BeforeRender extends JCckPluginField
{
	protected static $type	=	'code_beforerender';
	protected static $path;
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Construct
	
	// onCCK_FieldConstruct
	public function onCCK_FieldConstruct( $type, &$data = array() )
	{
		if ( self::$type != $type ) {
			return;
		}
		parent::g_onCCK_FieldConstruct( $data );
		$data['display']	=	0;
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Prepare
	
	// onCCK_FieldPrepareContent
	public function onCCK_FieldPrepareContent( &$field, $value = '', &$config = array() )
	{
		if ( self::$type != $field->type ) {
			return;
		}
		
		// Init
		$name		=	$field->name;
		
		// Prepare
		if ( $field->bool ) {
			if ( $field->options ) {
				$options	=	explode( '||', $field->options );
				if ( count( $options ) ) {
					parent::g_addProcess( 'beforeRenderContent', self::$type, $config, array( 'name'=>$name, 'mode'=>$field->bool, 'files'=>$options ) );
				}
			}
		} else {
			$options2	=	JCckDev::fromJSON( $field->options2 );
			if ( isset( $options2['code'] ) && $options2['code'] != '' ) {
				parent::g_addProcess( 'beforeRenderContent', self::$type, $config, array( 'name'=>$name, 'mode'=>$field->bool, 'code'=>$options2['code'] ) );
			}
		}
		
		// Set
		$field->state	=	true;
		$field->value	=	'';
	}
	
	// onCCK_FieldPrepareForm
	public function onCCK_FieldPrepareForm( &$field, $value = '', &$config = array(), $inherit = array(), $return = false )
	{
		if ( self::$type != $field->type ) {
			return;
		}
		
		// Init
		if ( count( $inherit ) ) {
			$name	=	( isset( $inherit['name'] ) && $inherit['name'] != '' ) ? $inherit['name'] : $field->name;
		} else {
			$name	=	$field->name;
		}
		
		// Prepare
		if ( $field->bool ) {
			if ( $field->options ) {
				$options	=	explode( '||', $field->options );
				if ( count( $options ) ) {
					parent::g_addProcess( 'beforeRenderForm', self::$type, $config, array( 'name'=>$name, 'mode'=>$field->bool, 'files'=>$options ) );
				}
			}
		} else {
			$options2	=	JCckDev::fromJSON( $field->options2 );
			if ( isset( $options2['code'] ) && $options2['code'] != '' ) {
				parent::g_addProcess( 'beforeRenderForm', self::$type, $config, array( 'name'=>$name, 'mode'=>$field->bool, 'code'=>$options2['code'] ) );
			}
		}
		
		// Set
		$field->form	=	'';
		$field->state	=	true;
		$field->value	=	'';
	}
	
	// onCCK_FieldPrepareSearch
	public function onCCK_FieldPrepareSearch( &$field, $value = '', &$config = array(), $inherit = array(), $return = false )
	{
		if ( self::$type != $field->type ) {
			return;
		}
		
		// Prepare
		self::onCCK_FieldPrepareForm( $field, $value, $config, $inherit, $return );
		
		// Return
		if ( $return === true ) {
			return $field;
		}
	}
	
	// onCCK_FieldPrepareStore
	public function onCCK_FieldPrepareStore( &$field, $value = '', &$config = array(), $inherit = array(), $return = false )
	{
		if ( self::$type != $field->type ) {
			return;
		}
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Render
	
	// onCCK_FieldRenderContent
	public static function onCCK_FieldRenderContent( $field, &$config = array() )
	{
		return parent::g_onCCK_FieldRenderContent( $field );
	}
	
	// onCCK_FieldRenderForm
	public static function onCCK_FieldRenderForm( $field, &$config = array() )
	{
		return parent::g_onCCK_FieldRenderForm( $field );
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Special Events
	
	// onCCK_FieldBeforeRenderContent
	public static function onCCK_FieldBeforeRenderContent( $process, &$fields, &$storages, &$config = array() )
	{
		self::_processing( $process, $fields, $storages, $config );
	}
	
	// onCCK_FieldBeforeRenderForm
	public static function onCCK_FieldBeforeRenderForm( $process, &$fields, &$storages, &$config = array() )
	{
		self::_processing( $process, $fields, $storages, $config );
	}

	// _process
	protected static function _processing( $process, &$fields, &$storages, &$config = array() )
	{
		$name	=	$process['name'];
		
		// Prepare
		if ( $process['mode'] ) {
			foreach ( $process['files'] as $file ) {
				if ( is_file( JPATH_SITE.'/'.$file ) ) {
					include JPATH_SITE.'/'.$file;
				}
			}
		} else {
			$code	=	$process['code'];
			eval( $code );
		}
	}
}
?>