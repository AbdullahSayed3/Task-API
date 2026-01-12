<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Traits\ApiResponse;

class ApiResponseTest extends TestCase
{
    // Use the trait in an anonymous class or just use the trait methods directly if possible.
    // Since trait methods are protected, we need a wrapper.
    
    public function test_success_response_structure()
    {
        $controller = new class {
            use ApiResponse;
            public function callSuccess($data, $msg, $code, $extra = []) {
                return $this->successResponse($data, $msg, $code, $extra);
            }
        };

        $data = ['id' => 1, 'name' => 'Test Item'];
        $extra = ['meta' => ['total' => 100], 'version' => '1.0'];

        $response = $controller->callSuccess($data, 'Operation successful', 200, $extra);

        $this->assertEquals(200, $response->status());
        
        $json = $response->getData(true);

        $this->assertTrue($json['success']);
        $this->assertEquals('Operation successful', $json['message']);
        $this->assertEquals($data, $json['data']);
        
        // Check extra fields
        $this->assertEquals($extra['meta'], $json['meta']);
        $this->assertEquals($extra['version'], $json['version']);
    }
}
