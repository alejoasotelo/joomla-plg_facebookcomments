<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.joomla
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Example Content Plugin
 *
 * @since  1.6
 */
class PlgContentFacebookComments extends JPlugin
{

	protected static $modules = array();

	protected static $mods = array();

	/**
	 * Plugin that loads module positions within content
	 *
	 * @param   string   $context   The context of the content being passed to the plugin.
	 * @param   object   &$article  The article object.  Note $article->text is also available
	 * @param   mixed    &$params   The article params
	 * @param   integer  $page      The 'page' number
	 *
	 * @return  mixed   true if there is an error. Void otherwise.
	 *
	 * @since   1.6
	 */
	public function onContentAfterDisplay($context, &$article, &$params, $limitstart = 0)
	{
		if ($context != 'com_content.article')
			return;

		$fb_id = $this->params->get('fbid');;
		$fb_lang = $this->params->get('lang');
		$fb_href = JURI::getInstance()->toString();
		$fb_width = $this->params->get('width');
		$fb_num_posts = $this->params->get('num_posts');

		$htag = $this->params->get('htag');
		$show_title = (bool)$this->params->get('show_title');
		$title = $this->params->get('title');
		$title_class = $this->params->get('title_class');

		$uid = uniqid();

		$widthHasPercentage = strpos($fb_width, '%') != false;

		$document = JFactory::getDocument();
		$document->addStyleSheet(JUri::base() .'plugins/content/facebookcomments/css/facebookcomments.css');

		$js = '
			//<![CDATA[

			window.fbAsyncInit = function() {

				FB.init({
			      appId      : "'.$fb_id.'",
			      xfbml      : true,
			      version    : "v2.6"
			    });

			    jQuery("#fbc-spinner-'.$uid .'").remove();
			};

			(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/'.$fb_lang.'/sdk.js"; /*#xfbml=1&version=v2.6&appId='.$fb_id.'*/
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, \'script\', \'facebook-jssdk\'));
			//]]>';

		$document->addScriptDeclaration($js);

		$html = '';

		if ($show_title)
			$html .= '<'.$htag.' '.(strlen($title_class) > 0 ? 'class="'.$title_class.'"' : '').'>'.$title.'</'.$htag.'>';

		$html .= '
			<div class="fb-comments" data-href="'.$fb_href.'" '.($fb_width > 0 ? 'data-width="'.$fb_width.'"': '').' data-numposts="'.$fb_num_posts.'"></div><div id="fbc-spinner-'.$uid .'"><div class="fbc-spinner">
  					<div class="fbc-double-bounce1"></div>
  					<div class="fbc-double-bounce2"></div>
				</div></div>';

		return $html;
	}
}
