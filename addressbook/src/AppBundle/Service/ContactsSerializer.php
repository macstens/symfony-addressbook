<?php

namespace AppBundle\Service;

use AppBundle\Entity\Contact;

class ContactsSerializer
{
    /**
     * @param $data
     * @return array
     * Serialize instance to array
     */
    public function serialize($data) : array {
        if (is_array($data) && !empty($data)) {

            $objectToArray = [];
            foreach ($data as $contact) {

                //log possible errors
                if ($contact instanceof Contact) {

                    $objectToArray[] = $this->serializeObject($contact);

                }
            }

            return $objectToArray;
        }

        //log possible errors
        if ($data !== null && $data instanceof Contact) {
            return $this->serializeObject($data);
        }

        return [];

    }

    /**
     * @param Contact $contact
     * @return array
     */
    public function serializeObject(Contact $contact) : array {
        $type = [
            'id' => $contact->getId()
        ];

        $contactArray = [
            'id'                => $contact->getId(),
            'firstname'         => $contact->getFirstName(),
            'lastname'          => $contact->getLastName(),
            'streetAndNumber'   => $contact->getStreetAndNumber(),
            'zip'               => $contact->getZip(),
            'city'              => $contact->getCity(),
            'country'           => $contact->getCountry(),
            'birthdate'         => ($contact->getBirthday() != null) ? $contact->getBirthday()->format('c') : null,
            'phonenumber'       => $contact->getPhonenumber(),
            'email'             => $contact->getEmail(),
            'picture'           => $contact->getPicture(),
            'createdAt'         => $contact->getCreatedAt()->format('c'),
            'updatedAt'         => $contact->getUpdatedAt()->format('c'),
        ];

        return $contactArray;
    }
}

