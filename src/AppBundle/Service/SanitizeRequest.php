<?php

namespace AppBundle\Service;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SanitizeRequest
 *
 * @author linkus
 */
class SanitizeRequest
{
    protected $default = [
        'language' => null,
        'tags' => [],
        'skills' => [],
    ];

    public function sanitize(array $req)
    {
        $error = [];
        $data = $this->default;

        if (isset($req['language'])) {
            if (preg_match('#^[-a-z0-9_]{1,20}$#', trim($req['language']))) {
                $data['language'] = trim($req['language']);
            } else {
                $error[] = 'language is not reconized';
            }
        }
        if (isset($req['tags'])) {
            $tags = explode(' ', trim($req['tags']));
            if (empty($tags)) {
                foreach ($tags as $tag) {
                    if (preg_match('#^[-a-z0-9 ]{1,20}$#', trim($tag))) {
                        $data['tags'] = trim($tag);
                    } else {
                        $error[] = 'tag "' . $tag . '" need to match pattern [-a-z0-9 ]{1,20}';
                    }
                }
            }
        }

        return [
            'data' => $data,
            'error' => $error,
        ];
    }

    public function getDefault()
    {
        return $this->default;
    }

}
