<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {

        // 1. Creer un fuax client de pointer vers une URL
        $client = static::createClient();
        $client->request('GET','/inscription');

        // 2. Remplir le champs de mon formulaire d'inscription
                // email-> register_user[email],
                // MDP-> register_user[plainPassword][first],
                // ConfMDP-> register_user[plainPassword][second],
                // Prenom-> register_user[firstname],
                // Nom-> register_user[lastname]
                // btn-> 'Valider'
        $client->submitForm('Valider',[
            'register_user[email]'=>'testJulie@registerUser.test',
            'register_user[plainPassword][first]'=>'123456',
            'register_user[plainPassword][second]'=>'123456',
            'register_user[firstname]'=>'test',
            'register_user[lastname]'=>'Julie'
        ]);

        // Teste notre redirection
        $this->assertResponseRedirects('/connexion');       // Teste notre redirection URL
        $client->followRedirect();  // Suivre notre redirection

        // 3. Est-ce que tu peux regarder si dans ma page j'ai le message d'allerte
        $this->assertSelectorExists('div:contains("Votre compte a etais creer, veuillez vous connecter")');        //permet de aller chercher les elements sur notre page


    }
}
