<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\User;
use App\Traits\GlobalResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\FeedBackViewResource;
use App\Http\Resources\FeedBackResource;
use App\Models\Comments;
use App\Http\Resources\CommentsResource;

class FeedbackController extends Controller
{
    use GlobalResponseTrait;

    protected $user,$feedback,$comment;

    public function __construct(User $user,Feedback $feedback,Comments $comment)
    {
        $this->user = $user;
        $this->feedback = $feedback;
        $this->comment = $comment;
    }

    public function index(Request $request){
        try{
            $feedbacks = $this->feedback->get();
            $response['feedbacks'] =  FeedBackResource::collection($feedbacks);
            return $this->returnResponse('', $response, 200);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->returnResponseError(999, false, 'Something went wrong. Please try again');
        }
    }

    public function create(Request $request){
        try {
            $data = $request->only([
                'title',
                'category',
                'description'
            ]);

            $validator = Validator::make(
                $data,
                    [
                        'title' => 'required|string',
                        'category' => 'required|integer|exists:categories,id',
                        'description' => 'required|string',
                    ]
            );

            if ($validator->fails()) {
                return $this->returnResponseError(422, 'Parameters are not valid.', $validator->errors());
            }

            $feedback_data = [
                'user_id' => auth()->user()->id,
                'title' => $data['title'],
                'category_id' => $data['category'],
                'body' => $data['description'],
            ];

            $response['feedback'] = $this->feedback->create($feedback_data);
            return $this->returnResponse('Feedback created Successfully', $response, 200);

        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->returnResponseError(999, false, 'Something went wrong. Please try again');
        }
    }

    public function view(Request $request,$feedback_id){
        try{
            $feedback = $this->feedback->find($feedback_id);

            if(!$feedback)
               return $this->returnResponseError(404, false, 'Record not found');
            else{
                $response['feedback'] = [
                    'title' => $feedback->title,
                    'category' => $feedback->category->title,
                    'body' => $feedback->body
                ];
                return $this->returnResponse('', $response, 200);
            }
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->returnResponseError(999, false, 'Something went wrong. Please try again');
        }
    }
}
