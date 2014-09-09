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
  private $map;

  function init()
  {
write_log('mail_skin', 'titi');
    $this->add_hook('message_part_after', array($this, 'replace'));
    $this->add_hook('message_outgoing_headers', array($this, 'replace_bis'));
    $this->add_hook('message_outgoing_body', array($this, 'replace_body'));
    $this->add_hook('message_compose_body', array($this, 'replace_compose_body'));
    $this->add_hook('message_before_send', array($this, 'replace_before_send'));
    $this->add_hook('message_sent', array($this, 'replace_sent_body'));
    $this->add_hook('message_part_after', array($this, 'replace_part_after'));
    $this->add_hook('message_compose', array($this, 'replace_compose'));
  
  }

  function replace_body($args)
  {
write_log('mail_skin', 'function replace_body appelee');
write_log('mail_skin', print_r($args, true));
$html_body = $args['body'];
write_log('mail_skin', 'args->body'.print_r($html_body, true));
  
$doc = new DOMDocument();
if (($args['type'] == 'html') && ($doc->loadHTML($html_body)))
{
	$html_elements = $doc->getElementsByTagName('html');
	$body_elements = $doc->getElementsByTagName('body');

	write_log('mail_skin', 'html_elements:'.print_r($html_elements, true));
	$a_head_element = $doc->createElement('head', ' ');
	$a_link_element = $doc->createElement('link', ' ');
	$a_href_attr = new DOMAttr('href', 'http://support-info.saint-lo.fr/logos/agglostlo/style_signature.css');
	$a_type_attr = new DOMAttr('type', 'text/css');
	$a_rel_attr = new DOMAttr('rel', 'stylesheet');
	$a_link_element->setAttributeNode($a_href_attr);
	$a_link_element->setAttributeNode($a_type_attr);
	$a_link_element->setAttributeNode($a_rel_attr);

	$a_returned_head = $html_elements->item(0)->insertBefore($a_head_element, $body_elements->item(0));
	$a_returned_head->appendChild($a_link_element);

	write_log('mail_skin', 'html_body_modifie:'.$doc->saveHTML());
        return array('body' => $doc->saveHTML());
}

    return null;
  }

  function replace_compose($args)
  {
write_log('mail_skin', 'function replace_compose appelee');
write_log('mail_skin', print_r($args, true));

    return null;
  }

  function replace_part_after($args)
  {
write_log('mail_skin', 'function replace_part_after appelee');
write_log('mail_skin', print_r($args, true));

    return null;
  }

  function replace_before_send($args)
  {
write_log('mail_skin', 'function replace_before_send appelee');
write_log('mail_skin', print_r($args, true));

    return null;
  }

  function replace_sent_body($args)
  {
write_log('mail_skin', 'function replace_sent_body appelee');
write_log('mail_skin', print_r($args, true));

    return null;
  }

  function replace_compose_body($args)
  {
write_log('mail_skin', 'function replace_compose_body appelee');
write_log('mail_skin', print_r($args, true));

/*
$html_body = $args['body'];
write_log('mail_skin', 'args->body'.print_r($html_body, true));
  
$doc = new DOMDocument();
if (($args['type'] == 'html') && ($doc->loadHTML($html_body)))
{
	$html_elements = $doc->getElementsByTagName('html');
	$body_elements = $doc->getElementsByTagName('body');

	write_log('mail_skin', 'html_elements:'.print_r($html_elements, true));
	$a_head_element = $doc->createElement('head', ' ');
	$a_link_element = $doc->createElement('link', ' ');
	$a_href_attr = new DOMAttr('href', 'http://support-info.saint-lo.fr/logos/agglostlo/style_signature.css');
	$a_type_attr = new DOMAttr('type', 'text/css');
	$a_rel_attr = new DOMAttr('rel', 'stylesheet');
	$a_link_element->setAttributeNode($a_href_attr);
	$a_link_element->setAttributeNode($a_type_attr);
	$a_link_element->setAttributeNode($a_rel_attr);

	$a_returned_head = $html_elements->item(0)->insertBefore($a_head_element, $body_elements->item(0));
	$a_returned_head->appendChild($a_link_element);

	write_log('mail_skin', 'html_body_modifie:'.$doc->saveHTML());
        return array('body' => $doc->saveHTML());
}
*/

    return null;
  }

  function replace_bis($args)
  {
write_log('mail_skin', 'function replace_bis appelee');
write_log('mail_skin', print_r($args, true));
  
    return null;
  }

  function replace($args)
  {
write_log('mail_skin', 'function replace appelee');
    if ($args['type'] == 'plain')
      return array('body' => strtr($args['body'], $this->map));
  
    return null;
  }
}
