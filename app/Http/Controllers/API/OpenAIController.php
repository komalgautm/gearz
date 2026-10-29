<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class OpenAIController extends Controller
{
    // Your OpenAI API key

    private $apiKey = '';

    private $automotiveKeywords = ["car", "automotive", "engine", "vehicle", "tire", "fuel", "transmission", "brake"];
    private $automotiveKeywords_ar = ["سيارة", "السيارات", "محرك", "عربة", "إطار العجلة", "وقود", "الانتقال", "الفرامل"];
    private function isRelevantToAutomotive($question, $language)
    {
        $questionLower = strtolower($question);
        if ($language == 2) {
            foreach ($this->automotiveKeywords_ar as $keyword) {
                if (strpos($questionLower, $keyword) !== false) {
                    return true;
                }
            }
            return false;
        } else {
            foreach ($this->automotiveKeywords as $keyword) {
                if (strpos($questionLower, $keyword) !== false) {
                    return true;
                }
            }
            return false;
        }
    }
    private function getChatGPTResponse($prompt, $language = 1)
    {
        try {
            // $client = new Client();
            // $url = 'https://api.openai.com/v1/engines/gpt-4o-mini/completions';
            // $response = $client->post($url, [
            //     'headers' => [
            //         'Authorization' => 'Bearer ' . $this->apiKey,
            //         'Content-Type' => 'application/json',
            //     ],
            //     'json' => [
            //         'prompt' => "Answer the following question, focusing only on cars and the automotive industry:\n$prompt",
            //         'max_tokens' => 150,
            //         'n' => 1,
            //         'stop' => null,
            //         'temperature' => 0.7,
            //     ],
            // ]);
            // $data = json_decode($response->getBody(), true);
            // $responseBody['status_code'] = $response->getStatusCode();
            // $responseBody['body'] = $data['choices'][0]['text'];
            //return $data['choices'][0]['text'] ?? 'No response generated.';
            $client = new Client();
            $url = 'https://api.openai.com/v1/chat/completions';

            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4', // Specify the correct chat model
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an expert in the automotive industry.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 150,
                    'temperature' => 0.7,
                    'n' => 1,
                    'stop' => null,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            //print_r($data);
            // Extracting the response
            $responseBody['status_code'] = $response->getStatusCode();
            $responseBody['body'] = $data['choices'][0]['message']['content'];
        } catch (\Exception $e) {
            //print_r($e->getMessage());
            $responseBody['status_code'] = 400;
        }
        return $responseBody;
    }
    private function filterResponse($response, $language)
    {
        $responseLower = strtolower($response);
        if ($language == 2) {
            foreach ($this->automotiveKeywords_ar as $keyword) {
                if (strpos($responseLower, $keyword) !== false) {
                    return $response;
                }
            }
            return "لم تتضمن الإجابة معلومات ذات صلة. يرجى طرح سؤال يتعلق بالسيارات وصناعة السيارات.";
        } else {
            foreach ($this->automotiveKeywords as $keyword) {
                if (strpos($responseLower, $keyword) !== false) {
                    return $response;
                }
            }
            return "The response did not contain relevant information. Please ask a question related to cars and the automotive industry.";
        }
    }
    public function handleUserInput(Request $request)
    {
        $userInput = $request->question;
        $language = $request->language;
        $userID = $request->userID;

        if ($this->isRelevantToAutomotive($userInput, $language)) {
            $response = $this->getChatGPTResponse($userInput, $language);
            //print_r($response);
            if ($response['status_code'] == 200) {
                $filteredResponse = $this->filterResponse($response['body'], $language);
                return response()->json(['success' => 'true', 'message' => '', 'result' => $filteredResponse], 200);
            } else {
                if ($language == 2) {
                    $filteredResponse = "هناك خطأ ما";
                } else {
                    $filteredResponse = "Something went wrong";
                }
                return response()->json(['success' => 'false', 'message' => $filteredResponse], 200);
            }
            //$filteredResponse = $this->filterResponse($response);
        } else {
            if ($language == 2) {
                $filteredResponse = "يرجى طرح الأسئلة المتعلقة بالسيارات وصناعة السيارات.";
            } else {
                $filteredResponse = "Please ask questions related to cars and the automotive industry.";
            }
            //$filteredResponse = "Please ask questions related to cars and the automotive industry.";
            return response()->json(['success' => 'true', 'message' => '', 'result' => $filteredResponse], 200);
        }

        // return response()->json(['response' => $filteredResponse]);
    }
}
