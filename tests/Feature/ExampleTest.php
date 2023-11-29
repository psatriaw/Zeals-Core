<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    private function getLogin(){
        $userData = [
            'username' => 'zealsasia',
            'password' => 'admin',
        ];
    
        // Attempt login
        $this->post('/login-auth', $userData);
    }

    // public function testDashboard()
    // {
    //     $this->getLogin();

    //     // Follow the direction after successful login
    //     $dashboardResponse = $this->get('/dashboard/view');

    //     // Assert that the dashboard access is successful
    //     $dashboardResponse->assertStatus(200);
    // }

    // public function testtestui()
    // {
    //     $this->getLogin();

    //     // Follow the direction after successful login
    //     $dashboardResponse = $this->get('testui');

    //     // Assert that the dashboard access is successful
    //     $dashboardResponse->assertStatus(200);
    // }

    // public function testAPIDashboard()
    // {
    //     $this->getLogin();

    //     // Follow the direction after successful login
    //     $dashboardResponse = $this->get('api/dashboard');

    //     // Assert that the dashboard access is successful
    //     $dashboardResponse->assertStatus(200);
    // }

    // public function testBanner()
    // {
    //     $this->getLogin();

    //     // Follow the direction after successful login
    //     $dashboardResponse = $this->get('master/banner');

    //     // Assert that the dashboard access is successful
    //     $dashboardResponse->assertStatus(200);
    // }

    // public function testTopUp()
    // {
    //     $this->getLogin();

    //     // Follow the direction after successful login
    //     $dashboardResponse = $this->get('master/topup');

    //     // Assert that the dashboard access is successful
    //     $dashboardResponse->assertStatus(200);
    // }

    // public function testTagihan()
    // {
    //     $this->getLogin();

    //     // Follow the direction after successful login
    //     $dashboardResponse = $this->get('master/tagihan');

    //     // Assert that the dashboard access is successful
    //     $dashboardResponse->assertStatus(200);
    // }

    // public function testPenerbit()
    // {
    //     $this->getLogin();

    //     // Follow the direction after successful login
    //     $dashboardResponse = $this->get('master/penerbit');

    //     // Assert that the dashboard access is successful
    //     $dashboardResponse->assertStatus(200);
    // }

    // public function testTutorial()
    // {
    //     $this->getLogin();

    //     // Follow the direction after successful login
    //     $dashboardResponse = $this->get('master/tutorial');

    //     // Assert that the dashboard access is successful
    //     $dashboardResponse->assertStatus(200);
    // }

    // public function testCampaign()
    // {
    //     $this->getLogin();

    //     // Follow the direction after successful login
    //     $dashboardResponse = $this->get('master/campaign');

    //     // Assert that the dashboard access is successful
    //     $dashboardResponse->assertStatus(200);
    // }

    // public function testWithdrawal()
    // {
    //     $this->getLogin();

    //     // Follow the direction after successful login
    //     $dashboardResponse = $this->get('master/withdrawal');

    //     // Assert that the dashboard access is successful
    //     $dashboardResponse->assertStatus(200);
    // }

    // public function testLaporanCampaign()
    // {
    //     $this->getLogin();

    //     // Follow the direction after successful login
    //     $dashboardResponse = $this->get('master/laporan-campaign');

    //     // Assert that the dashboard access is successful
    //     $dashboardResponse->assertStatus(200);
    // }

    // public function testCategory()
    // {
    //     $this->getLogin();

    //     // Follow the direction after successful login
    //     $dashboardResponse = $this->get('master/category');

    //     // Assert that the dashboard access is successful
    //     $dashboardResponse->assertStatus(200);
    // }

    // public function testFeeSetting()
    // {
    //     $this->getLogin();

    //     // Follow the direction after successful login
    //     $dashboardResponse = $this->get('master/feesetting');

    //     // Assert that the dashboard access is successful
    //     $dashboardResponse->assertStatus(200);
    // }

    // public function testCustodian()
    // {
    //     $this->getLogin();

    //     // Follow the direction after successful login
    //     $dashboardResponse = $this->get('custodian/user');

    //     // Assert that the dashboard access is successful
    //     $dashboardResponse->assertStatus(200);
    // }

    // public function testCustodianPurchase()
    // {
    //     $this->getLogin();

    //     // Follow the direction after successful login
    //     $dashboardResponse = $this->get('custodian/purchase');

    //     // Assert that the dashboard access is successful
    //     $dashboardResponse->assertStatus(200);
    // }

    // public function testRegister()
    // {
    //     $this->getLogin();

    //     // Follow the direction after successful login
    //     $dashboardResponse = $this->get('register');

    //     // Assert that the dashboard access is successful
    //     $dashboardResponse->assertStatus(200);
    // }

    public function testRegLink()
    {
        $this->getLogin();

        // Follow the direction after successful login
        $dashboardResponse = $this->get('reglink');

        // Assert that the dashboard access is successful
        $dashboardResponse->assertStatus(200);
    }

    public function testMasterQR()
    {
        $this->getLogin();

        // Follow the direction after successful login
        $dashboardResponse = $this->get('master-qr');

        // Assert that the dashboard access is successful
        $dashboardResponse->assertStatus(200);
    }
}
