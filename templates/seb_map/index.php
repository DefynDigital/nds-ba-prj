<?php
/**
* @version 			SEBLOD 3.x More ~ $Id: index.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2009 - 2016 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

defined( '_JEXEC' ) or die;

require_once dirname(__FILE__).'/config.php';
$cck	=	CCK_Rendering::getInstance( $this->template );
if ( $cck->initialize() === false ) { return; }

$map_id				=	'map_canvas'; //todo + map_iw_event
$map_width			=	$cck->getStyleParam( 'map_width', '600px' );
$map_height			=	$cck->getStyleParam( 'map_height', '375px' );
$map_lang			=	substr( JFactory::getLanguage()->getTag(), 0, 2 );
$scheme				=	JUri::getInstance()->getScheme();

$map_key			=	$cck->getTemplateParam( 'api_key' );
$map_key			=	( $map_key ) ? 'key='.$map_key.'&' : '';

$map_mk_icon		=	$cck->getStyleParam( 'map_marker_icon', 'red-dot.png' );
$map_mk_icon_base	=	$scheme.'://maps.google.com/intl/en_us/mapfiles/ms/micons/';
if ( $map_mk_icon == '-1' ) {
	$map_mk_icon		=	$cck->getStyleParam( 'map_marker_icon_custom', '' );
	$map_mk_icon_base	=	JUri::root( true ).'/';
}
$map_mk_icon_path	=	$cck->getStyleParam( 'map_marker_icon_dynamicpath', '' );
if ( !$map_mk_icon_path ) {
	$map_mk_icon_path	=	addslashes( $scheme.'://maps.google.com/intl/en_us/mapfiles/ms/micons/' );
} elseif ( strpos( $map_mk_icon_path, 'http' ) === false ) {
	$map_mk_icon_path	=	addslashes( JUri::root( true ).'/'.$map_mk_icon_path );
}

$map_mk_animation	=	$cck->getStyleParam( 'map_marker_init_animation', '' );
$map_mk_animation	=	$map_mk_animation ? $map_mk_animation : '""';
$map_mk_timeout		=	$cck->getStyleParam( 'map_marker_init_timeout', 0 );
$map_mk_timeoutms	=	$cck->getStyleParam( 'map_marker_init_timeoutms', '200' );
$map_iw_event		=	$cck->getStyleParam( 'map_infowindow_openevent', 'click' );
$map_styles			=	$cck->getStyleParam( 'map_styles', '' );
$map_styles			=	( $map_styles ) ? $map_styles : '[]';

$items				=	$cck->getItems();
$js					=	'';
$markers			=	array();

$clustering			=	(int)$cck->getStyleParam( 'map_marker_clustering', '0' );
$zoom				=	(int)$cck->getStyleParam( 'map_zoom', 2 );
$zoom_auto			=	(int)$cck->getStyleParam( 'map_marker_autozoom', '0' );

$function			=	'setMarker';
$infowindow			=	$cck->getFields( 'infowindow', '', false );
$locations			=	$cck->getFields( 'location', '', false );
$multiple			=	( count( $infowindow ) > 1 ) ? true : false;
$f_icon				=	'';
if ( $cck->getStyleParam( 'map_marker_icon_dynamic', 0 ) ) {
	$f_icon			=	$cck->getFields( 'markericon', '', false );
	$f_icon			=	isset( $f_icon[0] ) ? $f_icon[0] : '';
}

// Set
$class				=	trim( $cck->id_class );
$isMore				=	$cck->isLoadingMore();
if ( $cck->isGoingToLoadMore() ) {
	$class			.=	' cck-loading-more';
}
$class				=	( $class ) ? ' class="'.$class.'"' : '';

if ( count( $items ) ) {
	if ( count( $locations ) ) {
		$js				=	'var geocoder = new google.maps.Geocoder();';
		$markers		=	'';
		$function		=	'setMarkerByGeolocation';
		foreach ( $items as $i=>$item ) {
			$html		=	'';
			$location	=	'';
			$icon		=	'';
			if ( $f_icon ) {
				$icon	=	$item->getValue( $f_icon );
				$icon	=	$icon ? $map_mk_icon_path.$icon : $icon;
			}
			foreach ( $locations as $loc ) {
				$location	.=	$item->getValue( $loc ).' ';
			}
			foreach ( $infowindow as $iw ) {
				$content	=	$item->renderField( $iw );
				if ( $content != '' ) {
					if ( $item->getMarkup( $iw ) != 'none' && ( $multiple || $item->getMarkup_Class( $iw ) ) ) {
						$html	.=	'<div class="cck-clrfix'.$item->getMarkup_Class( $iw ).'">'.$content.'</div>';
					} else {
						$html	.=	$content;
					}
				}
			}
			$markers[]	=	array( $html, $icon, trim( $location ) );
		}
	} else {
		$function		=	'setMarker';
		$latitude		=	$cck->getFields( 'latitude', '', false );
		$f_latitude		=	$latitude[0];
		$longitude		=	$cck->getFields( 'longitude', '', false );
		$f_longitude	=	$longitude[0];
		
		$markers		=	'';
		foreach ( $items as $i=>$item ) {
			$html		=	'';
			$lat		=	$item->getValue( $f_latitude );
			$lat		=	( $lat != '' ) ? $lat : 0;
			$lng		=	$item->getValue( $f_longitude );
			$lng		=	( $lng != '' ) ? $lng : 0;
			$icon		=	'';
			if ( $lat == 0 && $lng == 0 ) {
				continue;
			}
			if ( $f_icon ) {
				$icon	=	$item->getValue( $f_icon );
				$icon	=	$icon ? $map_mk_icon_path.$icon : $icon;
			}
			foreach ( $infowindow as $iw ) {
				$content	=	$item->renderField( $iw );
				if ( $content != '' ) {
					if ( $item->getMarkup( $iw ) != 'none' && ( $multiple || $item->getMarkup_Class( $iw ) ) ) {
						$html	.=	'<div class="cck-clrfix'.$item->getMarkup_Class( $iw ).'">'.$content.'</div>';
					} else {
						$html	.=	$content;
					}
				}
			}
			$markers[]	=	array( $html, $icon, $lat, $lng );
		}
	}
	$markers	=	json_encode( $markers );
}

if ( $isMore ) {
	$js	=	'jQuery(document).ready(function(){ JCck.More.SebMap.setMarkers("'.$function.'", map, ['.$markers.'], bounds, "'.$map_mk_icon_base.$map_mk_icon.'", {"anim":'.$map_mk_animation.',"timeout":'.$map_mk_timeout.',"timeoutms":"'.$map_mk_timeoutms.'"}, '.$zoom.', '.$zoom_auto.', '.$clustering.', clusters); });';
} else {
	$js	.=	'
			var bounds;
			var clusters = [];
			var infowindow = new google.maps.InfoWindow({});
			var map;
			var marker = null;
			if("undefined"===typeof JCck.More){JCck.More={}};
			JCck.More.SebMap={};
			function initMap_'.$map_id.'() {
				var myOptions = {
					center: new google.maps.LatLng('.$cck->getStyleParam( 'map_lat', '48.856614' ).', '.$cck->getStyleParam( 'map_lng', '2.3522219000000177' ).'),
					mapTypeControl: '.$cck->getStyleParam( 'map_typecontrol', 'true' ).',
					mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.'.$cck->getStyleParam( 'map_typecontrolstyle', 'DEFAULT' ).'},
					mapTypeId: google.maps.MapTypeId.'.$cck->getStyleParam( 'map_typeid', 'ROADMAP' ).',
					panControl: '.$cck->getStyleParam( 'map_pancontrol', 'true' ).',
					scrollwheel: '.$cck->getStyleParam( 'map_scrollwheel', 'true' ).',
					streetViewControl: '.$cck->getStyleParam( 'map_streetviewcontrol', 'true' ).',
					styles: '.$map_styles.',
					zoomControl: '.$cck->getStyleParam( 'map_zoomcontrol', 'true' ).',
					zoomControlOptions: {style: google.maps.ZoomControlStyle.'.$cck->getStyleParam( 'map_zoomcontrolstyle', 'DEFAULT' ).'},
					zoom: '.$zoom.'
				};
				bounds = new google.maps.LatLngBounds();
				map = new google.maps.Map(document.getElementById("'.$map_id.'"),myOptions);
				var markers = '.$markers.';
				var icon = "'.$map_mk_icon_base.$map_mk_icon.'";
				var animation = {"anim":'.$map_mk_animation.',"timeout":'.$map_mk_timeout.',"timeoutms":"'.$map_mk_timeoutms.'"};
				var fct = "'.$function.'";
				JCck.More.SebMap.setMarkers(fct, map, markers, bounds, icon, animation, '.$zoom.', '.$zoom_auto.', '.$clustering.', clusters);
			}
			JCck.More.SebMap.setMarkers = function(fct, map, markers, bounds, icon, animation, zoom, autozoom, clustering, clusters) {
				iterator = 0; iterator2 = 0;
				var anim = animation.anim ? animation.anim : null;
				if (animation.timeout == 1) {
					for (var i = 0; i < markers.length; i++) { setTimeout(function() { JCck.More.SebMap[fct](map, markers, bounds, icon, anim, animation.timeout, zoom, autozoom, clustering, clusters); }, i * animation.timeoutms); }
				} else {
					for (var i = 0; i < markers.length; i++) {
						JCck.More.SebMap[fct](map, markers, bounds, icon, anim, animation.timeout, zoom, autozoom, clustering, clusters);
					}
				}
			}
			JCck.More.SebMap.setMarker = function(map, markers, bounds, icon, anim, timeout, zoom, autozoom, clustering, clusters) {
				if(markers[iterator][1]) { icon = markers[iterator][1]; }
				var latLng = new google.maps.LatLng(markers[iterator][2], markers[iterator][3]);
				marker = new google.maps.Marker({ map:map, position: latLng, mycontent:markers[iterator][0], icon:icon, animation:anim });
				if (clustering) {
					clusters.push(marker);
				}
				google.maps.event.addListener(marker, "'.$map_iw_event.'", function(){ if(this.mycontent){infowindow.setContent(this.mycontent); infowindow.open(map,this);} });	
				bounds.extend(latLng);
				iterator++;
				if (clustering && (iterator == markers.length)) {
					var mco = {gridSize:60, imageExtension:"'.$cck->getStyleParam('map_marker_clustering_extension', 'png').'", imagePath:"'.$cck->base.'/templates/'.$cck->template.'/images/'.$cck->getStyleParam('map_marker_clustering_extension', 'png').'/m"};
					var mc = new MarkerClusterer(map, clusters, mco);
				}
				if (iterator == markers.length) {
					JCck.More.SebMap.setZoom(map, markers, bounds, autozoom, zoom);
				}
			}
			JCck.More.SebMap.setMarkerByGeolocation = function(map, markers, bounds, icon, anim, timeout, zoom, autozoom, clustering) {
				if(markers[iterator][1]) { icon = markers[iterator][1]; }
				geocoder.geocode( {"address":markers[iterator][2]}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						marker = new google.maps.Marker({ map:map, position:results[0].geometry.location, mycontent:markers[iterator2][0], icon:icon, animation:anim });
						if (clustering) {
							clusters.push(marker);
						}
						google.maps.event.addListener(marker, "'.$map_iw_event.'", function(){ if(this.mycontent){infowindow.setContent(this.mycontent); infowindow.open(map,this);} });
						bounds.extend(results[0].geometry.location);
						iterator2++;
						if (clustering && (iterator2 == markers.length)) {
							var mco = {gridSize:60, imageExtension:"'.$cck->getStyleParam('map_marker_clustering_extension', 'png').'", imagePath:"'.$cck->base.'/templates/'.$cck->template.'/images/'.$cck->getStyleParam('map_marker_clustering_extension', 'png').'/m"};
							var mc = new MarkerClusterer(map, clusters, mco);
						}
						if (iterator2 == markers.length) {
							JCck.More.SebMap.setZoom(map, markers, bounds, autozoom, zoom);
						}
					}
				});
				iterator++;
			}
			JCck.More.SebMap.setZoom = function(map, markers, bounds, autozoom, zoom, mode) {
				if (!autozoom) {
					return;
				}
				if(markers.length > 1) {
					map.fitBounds(bounds);
				} else if (markers.length == 1) {
					map.setCenter(bounds.getCenter());
  					map.setZoom(zoom);
				} else {
					map.setCenter(myOptions.center);
				}
			}
			jQuery(document).ready(function(){ initMap_'.$map_id.'(); });
			';

	$doc	=	JFactory::getDocument();
	$lib	=	$scheme.'://maps.googleapis.com/maps/api/js?'.$map_key.'language='.$map_lang;

	if ( method_exists( 'JCckDev', 'addScript' ) ) {
		JCckDev::addScript( $lib );
	} else {
		$doc->addScript( $lib );
	}
	if ( $clustering ) {
		$doc->addScript( $cck->base.'/templates/'.$cck->template.'/js/markerclusterer.min.js' );
	}
	$doc->addScriptDeclaration( $js );
}

if ( !$isMore ) {
?>
<div id="<?php echo $cck->id; ?>"<?php echo $class; ?>>
	<div id="<?php echo $map_id; ?>" style="width:<?php echo $map_width; ?>; height:<?php echo $map_height; ?>;"></div>
</div>
<?php } else { ?>
<script type="text/javascript">
	<?php echo $js; ?>
</script>
<?php } ?>