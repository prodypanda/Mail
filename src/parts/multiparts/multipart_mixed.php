<?php
/**
 * File containing the ezcMailMultipartMixed class
 *
 * @package Mail
 * @version //autogen//
 * @copyright Copyright (C) 2005, 2006 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * The mixed multipart type is used to bundle an ordered list of mail
 * parts.
 *
 * Each part will be shown in the mail in the order provided.
 *
 * The following example shows how to build a mail with a text part
 * and an attachment using ezcMailMultipartMixed.
 * <code>
 *        $mixed = new ezcMailMultipartMixed( new ezcMailTextPart( "Picture of me flying!" ),
 *                                            new ezcMailFilePart( "fly.jpg" ) );
 *        $mail = new ezcMail();
 *        $mail->body = $mixed;
 * </code>
 *
 * @package Mail
 * @version //autogen//
 */
class ezcMailMultipartMixed extends ezcMailMultipart
{
    /**
     * Constructs a new ezcMailMultipartMixed
     *
     * The constructor accepts an arbitrary number of ezcMailParts or arrays with ezcMailparts.
     * Parts are added in the order provided. Parameters of the wrong
     * type are ignored.
     *
     * @param ezcMailPart|array(ezcMailPart)
     * @return void
     */
    public function __construct()
    {
        $args = func_get_args();
        parent::__construct( $args );
    }

    /**
     * Appends a part to the list of parts.
     *
     * @param ezcMailpart $part
     * @return void
     */
    public function appendPart( ezcMailpart $part )
    {
        $this->parts[] = $part;
    }

    /**
     * Returns the mail parts associated with this multipart.
     *
     * @return array(ezcMailPart)
     */
    public function getParts()
    {
        return $this->parts;
    }

    /**
     * Returns "mixed".
     *
     * @return string
     */
    public function multipartType()
    {
        return "mixed";
    }
}
?>