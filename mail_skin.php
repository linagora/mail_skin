<?php
/**
 *
 * Plugin developpe pour l agglomeration de St Lo
 *
 * @version 1.0
 * @author 
 * @url 
 */
class mail_skin extends rcube_plugin
{
  public $task = 'mail';
  public $link_url = 'http://support-info.saint-lo.fr/logos/agglostlo/style_signature.css';
  private $map;

  function init()
  {
    $this->add_hook('message_outgoing_body', array($this, 'replace_body'));
  
  }

  function replace_body($args)
  {
	$html_body = $args['body'];
	  
	$doc = new DOMDocument();
	if (($args['type'] == 'html') && ($doc->loadHTML($html_body)))
	{
		$html_elements = $doc->getElementsByTagName('html');
		$body_elements = $doc->getElementsByTagName('body');

		$a_head_element = $doc->createElement('head', ' ');
		$a_link_element = $doc->createElement('link', ' ');
		$a_href_attr = new DOMAttr('href', $this->link_url);
		$a_type_attr = new DOMAttr('type', 'text/css');
		$a_rel_attr = new DOMAttr('rel', 'stylesheet');
		$a_link_element->setAttributeNode($a_href_attr);
		$a_link_element->setAttributeNode($a_type_attr);
		$a_link_element->setAttributeNode($a_rel_attr);

		$a_returned_head = $html_elements->item(0)->insertBefore($a_head_element, $body_elements->item(0));
		$a_returned_head->appendChild($a_link_element);

		return array('body' => $doc->saveHTML());
	}

        return null;
  }

}
