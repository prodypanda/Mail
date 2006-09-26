<?php
/**
 * @copyright Copyright (C) 2005, 2006 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version //autogentag//
 * @filesource
 * @package Mail
 * @subpackage Tests
 */

/**
 * @package Mail
 * @subpackage Tests
 */
class ezcMailTransportImapTest extends ezcTestCase
{
    public function testInvalidServer()
    {
        try
        {
            $imap = new ezcMailImapTransport( "no.such.server.example.com" );
            $this->fail( "Didn't get exception when expected" );
        }
        catch ( ezcMailTransportException $e )
        {
            $this->assertEquals( 'An error occured while sending or receiving mail. Failed to connect to the server: no.such.server.example.com:143.', $e->getMessage() );
        }
    }

    public function testInvalidUsername()
    {
        try
        {
            $imap = new ezcMailImapTransport( "dolly.ez.no" );
            $imap->authenticate( "no_such_user", "ezcomponents" );
            $this->fail( "Didn't get exception when expected" );
        }
        catch ( ezcMailTransportException $e )
        {
            $this->assertEquals( 'An error occured while sending or receiving mail. The IMAP server did not accept the username and/or password.', $e->getMessage() );
        }
    }

    public function testInvalidPassword()
    {
        try
        {
            $imap = new ezcMailImapTransport( "dolly.ez.no" );
            $imap->authenticate( "ezcomponents", "no_such_password" );
            $this->fail( "Didn't get exception when expected" );
        }
        catch ( ezcMailTransportException $e )
        {
            $this->assertEquals( 'An error occured while sending or receiving mail. The IMAP server did not accept the username and/or password.', $e->getMessage() );
        }
    }


    public function testInvalidCallListMessages()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->disconnect();
        try
        {
            $imap->listMessages();
            $this->fail( "Didn't get exception when expected" );
        }
        catch ( ezcMailTransportException $e )
        {
            $this->assertEquals( 'An error occured while sending or receiving mail. Can\'t call listMessages() on the IMAP transport when a mailbox is not selected.', $e->getMessage() );
        }
    }

    public function testInvalidCallTop()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->disconnect();
        try
        {
            $imap->top( 1, 1 );
            $this->fail( "Didn't get exception when expected" );
        }
        catch ( ezcMailTransportException $e )
        {
            $this->assertEquals( 'An error occured while sending or receiving mail. Can\'t call top() on the IMAP transport when a mailbox is not selected.', $e->getMessage() );
        }
    }

    public function testInvalidCallStatus()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->disconnect();
        try
        {
            $imap->status( $a, $b );
            $this->fail( "Didn't get exception when expected" );
        }
        catch ( ezcMailTransportException $e )
        {
            $this->assertEquals( 'An error occured while sending or receiving mail. Can\'t call status() on the IMAP transport when a mailbox is not selected.', $e->getMessage() );
        }
    }

    public function testInvalidCallListUniqueMessages()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->disconnect();
        try
        {
            $imap->listUniqueIdentifiers();
            $this->fail( "Didn't get exception when expected" );
        }
        catch ( ezcMailTransportException $e )
        {
            $this->assertEquals( 'An error occured while sending or receiving mail. Can\'t call listUniqueIdentifiers() on the IMAP transport when a mailbox is not selected.', $e->getMessage() );
        }
    }

    public function testInvalidCallSelectMailbox()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->disconnect();
        try
        {
            $imap->selectMailbox( 'inbox' );
            $this->fail( "Didn't get exception when expected" );
        }
        catch ( ezcMailTransportException $e )
        {
            $this->assertEquals( 'An error occured while sending or receiving mail. Can\'t select a mailbox when not successfully logged in.', $e->getMessage() );
        }
    }

    public function testInvalidSelectMailbox()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->authenticate( "ezcomponents", "ezcomponents" );
        try
        {
           $imap->selectMailbox( 'no-such-mailbox' );
           $this->fail( "Didn't get exception when expected" );
        }
        catch ( ezcMailTransportException $e )
        {
            $this->assertEquals( 'An error occured while sending or receiving mail. Mailbox <no-such-mailbox> does not exist on the IMAP server.', $e->getMessage() );
        }
    }

    public function testFetchMail()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->authenticate( "ezcomponents", "ezcomponents" );
        $imap->selectMailbox( 'inbox' );
        $set = $imap->fetchAll();
        $parser = new ezcMailParser();
        $mail = $parser->parseMail( $set );
        $this->assertEquals( 4, count( $mail ) );
    }

    public function testListMessages()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->authenticate( "ezcomponents", "ezcomponents" );
        $imap->selectMailbox( 'inbox' );
        $list = $imap->listMessages();
        $this->assertEquals( array( 1 => '1723', 2 => '1694', 3 => '1537', 4 => '64070' ), $list );
    }

    public function testFetchByMessageNr1()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->authenticate( "ezcomponents", "ezcomponents" );
        $imap->selectMailbox( 'inbox' );
        try
        {
            $message = $imap->fetchByMessageNr( -1 );
            $this->assertEquals( 'Expected exception was not thrown' );
        }
        catch ( ezcMailNoSuchMessageException $e )
        {
            $this->assertEquals( 'The message with ID <-1> could not be found.', $e->getMessage() );
        }
    }

    public function testFetchByMessageNr2()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->authenticate( "ezcomponents", "ezcomponents" );
        $imap->selectMailbox( 'inbox' );
        try
        {
            $message = $imap->fetchByMessageNr( 0 );
            $this->assertEquals( 'Expected exception was not thrown' );
        }
        catch ( ezcMailNoSuchMessageException $e )
        {
            $this->assertEquals( 'The message with ID <0> could not be found.', $e->getMessage() );
        }
    }

    public function testFetchByMessageNr3()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->authenticate( "ezcomponents", "ezcomponents" );
        $imap->selectMailbox( 'inbox' );
        $message = $imap->fetchByMessageNr( 1 );
        $parser = new ezcMailParser();
        $mail = $parser->parseMail( $message );
        $this->assertEquals( 1, count( $mail ) );
        $this->assertEquals( array( 0 => '1' ), $this->getAttribute( $message, 'messages' ) );
        $this->assertEquals( 'ezcMailImapSet', get_class( $message ) );
    }

    public function testfetchFromOffset1()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->authenticate( "ezcomponents", "ezcomponents" );
        $imap->selectMailbox( 'inbox' );
        try
        {
            $set = $imap->fetchFromOffset( -1, 10 );
            $this->assertEquals( 'Expected exception was not thrown' );
        }
        catch ( ezcMailOffsetOutOfRangeException $e )
        {
            $this->assertEquals( 'The offset <-1> is outside of the message subset <-1, 10>.', $e->getMessage());
        }
    }

    public function testfetchFromOffset2()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->authenticate( "ezcomponents", "ezcomponents" );
        $imap->selectMailbox( 'inbox' );
        try
        {
            $set = $imap->fetchFromOffset( 10, 1 );
            $this->assertEquals( 'Expected exception was not thrown' );
        }
        catch ( ezcMailOffsetOutOfRangeException $e )
        {
            $this->assertEquals( 'The offset <10> is outside of the message subset <10, 1>.', $e->getMessage() );
        }
    }

    public function testfetchFromOffset3()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->authenticate( "ezcomponents", "ezcomponents" );
        $imap->selectMailbox( 'inbox' );
        try
        {
            $set = $imap->fetchFromOffset( 0, -1 );
            $this->assertEquals( 'Expected exception was not thrown' );
        }
        catch ( ezcMailInvalidLimitException $e )
        {
            $this->assertEquals( 'The message count <-1> is not allowed for the message subset <0, -1>.', $e->getMessage() );
        }
    }

    public function testfetchFromOffset4()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->authenticate( "ezcomponents", "ezcomponents" );
        $imap->selectMailbox( 'inbox' );
        $set = $imap->fetchFromOffset( 1, 4 );
        $parser = new ezcMailParser();
        $mail = $parser->parseMail( $set );
        $this->assertEquals( 4, count( $mail ) );
        $this->assertEquals( "pine: Mail with attachment", $mail[1]->subject );
    }

    public function testStatus()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->authenticate( "ezcomponents", "ezcomponents" );
        $imap->selectMailbox( 'inbox' );
        $imap->status( $num, $size );
        $this->assertEquals( 4, $num );
        $this->assertEquals( 69024, $size );
    }

    public function testTop()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->authenticate( "ezcomponents", "ezcomponents" );
        $imap->selectMailbox( 'inbox' );
        $list = $imap->top( 1, 1 );
        // we do a simple test here.. Any non-single line reply here is 99.9% certainly a good reply
        $this->assertEquals( true, count( explode( "\n", $list ) ) > 1 );
    }

    public function testListUniqueIdentifiersSingle()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->authenticate( "ezcomponents", "ezcomponents" );
        $imap->selectMailbox( 'inbox' );
        $uids = $imap->listUniqueIdentifiers( 1 );
        $this->assertEquals( array( 1 => 52 ), $uids );
    }

    public function testListUniqueIdentifiersMultiple()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->authenticate( "ezcomponents", "ezcomponents" );
        $imap->selectMailbox( 'inbox' );
        $uids = $imap->listUniqueIdentifiers();
        $this->assertEquals(
            array(
                1 => 52,
                2 => 53,
                3 => 54,
                4 => 55,
            ),
            $uids
        );
    }

    public function testDisconnect()
    {
        $imap = new ezcMailImapTransport( "dolly.ez.no" );
        $imap->authenticate( "ezcomponents", "ezcomponents" );
        $imap->disconnect();
        $imap->disconnect();
    }

    public static function suite()
    {
         return new ezcTestSuite( "ezcMailTransportImapTest" );
    }
}
?>