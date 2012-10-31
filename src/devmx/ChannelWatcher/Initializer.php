<?php

/**
 * This file is part of the Teamspeak3 ChannelWatcher.
 * Copyright (C) 2012 drak3 <drak3@live.de>
 * Copyright (C) 2012 Maxe <maxe.nr@live.de>
 *
 * The Teamspeak3 ChannelWatcher is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The Teamspeak3 ChannelWatcher is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the Teamspeak3 ChannelWatcher.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace devmx\ChannelWatcher;

use devmx\ChannelWatcher\Storage\StorageInterface;
use devmx\ChannelWatcher\Storage\InitableStorageInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Description of Initializer
 *
 * @author drak3
 */
class Initializer {
    
    protected $storageDir;
    
    protected $storage;
    
    protected $fs;
    
    public function __construct(StorageInterface $storage, $storageDir) {
        $this->storage = $storage;
        $this->storageDir = $storageDir;
        $this->fs = new Filesystem();
    }
    
    public function setFilesystem(Filesystem $fs) {
        $this->fs = $fs;
    }
    
    public function initEnviroment() {
        $this->initStorageDir();
        $this->initStorage();
    }
    
    private function initStorageDir() {
        if(!$this->storageDirIsInited()) {
            $this->fs->mkdir($this->storageDir);
        }        
    }
    
    private function initStorage() {
        if($this->storage instanceof InitableStorageInterface && !$this->storageIsInited()) {
            $this->storage->init();
        }
    }
    
    private function storageDirIsInited() {
        return $this->fs->exists($this->storageDir);
    }
    
    private function storageIsInited() {
        return $this->storage->isInited();
    }
    
}

?>
