<?php

/**
 *
 * @version 0.0.1: CreateJoomlaSiteTrait.php 26/08/2016T23:12
 * @author Clifton IT Foundries Pty Ltd
 * @link http://cliftonwebfoundry.com.au
 * @copyright Copyright (c) 2016 Clifton IT Foundries Pty Ltd. All rights Reserved
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 **/
class JoomlaSiteHelper
{

    // SSH Host
    private $ssh_host = '127.0.0.1';
    // SSH Port
    private $ssh_port = 2222;
    // SSH Username
    private $ssh_auth_user = 'vagrant';
    // SSH Public Key File
    private $ssh_auth_pub = '/Users/nclifton/vagrant/joomlatools/.vagrant/machines/default/virtualbox/public_key';
    // SSH Private Key File
    private $ssh_auth_priv = '/Users/nclifton/vagrant/joomlatools/.vagrant/machines/default/virtualbox/private_key';
    // SSH Connection
    private $connection;


    public function createSite($siteName)
    {
        return $this->executeCommand('/home/vagrant/.composer/vendor/bin/joomla -vvv site:create ' . $siteName);
    }

    public function deleteSite($siteName)
    {
        return $this->executeCommand('/home/vagrant/.composer/vendor/bin/joomla -vvv site:delete ' . $siteName);
    }

    public function installExtension($siteName, $installFile)
    {
        return $this->executeCommand('/home/vagrant/.composer/vendor/bin/joomla -vvv extension:installfile ' . $siteName . ' ' . $installFile);
    }

    public function extensionSymLink($siteName, $installFile)
    {
        $projectsDir = defined('JOOMLATOOLS_PROJECTS_DIR') ? JOOMLATOOLS_PROJECTS_DIR : '';
        $projectsDirOption = empty($projectsDir) ? '' : ' --projects_dir ' . $projectsDir;

        return $this->executeCommand(
            '/home/vagrant/.composer/vendor/bin/joomla -vvv extension:symlink' .
            $projectsDirOption . ' ' . $siteName . ' ' . $installFile);
    }

    private function connect()
    {
        if (!($this->connection = ssh2_connect($this->ssh_host, $this->ssh_port))) {
            throw new Exception('Cannot connect to server');
        }

        if (!ssh2_auth_pubkey_file($this->connection, $this->ssh_auth_user, $this->ssh_auth_pub, $this->ssh_auth_priv)) {
            throw new Exception('Autentication rejected by server');
        }
    }

    private function executeRemoteCommand($cmd)
    {
        if (!($stream = ssh2_exec($this->connection, $cmd))) {
            throw new Exception('SSH command failed');
        }
        stream_set_blocking($stream, true);
        $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
        $data = stream_get_contents($stream_out);
        return $data;
    }

    private function disconnect()
    {
        $this->executeRemoteCommand('echo "EXITING" && exit;');
        $this->connection = null;
    }


    public function __destruct()
    {
        if (!is_null($this->connection))
            $this->disconnect();
    }

    /**
     * @param $cmd
     *
     * @return string
     *
     * @since version
     */
    private function executeCommand($cmd)
    {
        if (!defined('USE_SSH2') || USE_SSH2 == false) {
            return shell_exec($cmd);
        } else {
            $this->connect();
            return $this->executeRemoteCommand($cmd);
        }
    }


}