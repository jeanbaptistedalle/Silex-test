<?php

namespace Test\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Test\Domain\Character;

class CharacterDAO extends DAO {

    public function find($id) {
        $sql = "select * from t_character where char_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            return null;
    }

    public function findByName($name) {
        $sql = "select * from t_character where char_name=?";
        $row = $this->getDb()->fetchAssoc($sql, array($name));

        if ($row)
            return $this->buildDomainObject($row);
        else
            return null;
    }
    
    public function nameAlreadyExists($character){
        $sql = "select char_id from t_character where char_name = ? and (char_id != ? OR (char_id != ?) IS NULL)";
        $row = $this->getDb()->fetchAssoc($sql, array($character->getName(), $character->getId(), $character->getId()));
        return $row;
    }

    public function findAll() {
        $sql = "select * from t_character order by char_name";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['char_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    public function save(Character $character) {
        $characterData = array(
            'char_name' => $character->getName()
        );
        if ($character->getId()) {
            // The article has already been saved : update it
            $this->getDb()->update('t_character', $characterData, array('char_id' => $character->getId()));
        } else {
            // The article has never been saved : insert it
            $this->getDb()->insert('t_character', $characterData);
            // Get the id of the newly created article and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $character->setId($id);
        }
    }

    public function delete($id) {
        // Delete the article
        $this->getDb()->delete('t_character', array('char_id' => $id));
    }

    protected function buildDomainObject($row) {
        $character = new Character();
        $character->setId($row['char_id']);
        $character->setName($row['char_name']);
        return $character;
    }

}
