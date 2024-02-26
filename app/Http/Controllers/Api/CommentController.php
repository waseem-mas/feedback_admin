<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\User;
use App\Models\Comments;
use App\Traits\GlobalResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CommentsResource;

class CommentController extends Controller
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
        try {
            $comments = $this->comment
                            ->with('replies')
                            ->where('feedback_id',$request->feedback_id)
                            ->whereNull('parent_id')
                            ->get();
            $response['comments'] = CommentsResource::collection($comments);
            return $this->returnResponse('Comments', $response, 200);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->returnResponseError(999, 'Something went wrong. Please try again', '');
        }
    }

    public function create(Request $request){
        try {
            $data = $request->only([
                'feedback_id',
                'parent_id',
                'body'
            ]);
           // return response()->json($data['feedback_id']);
            $validator = Validator::make(
                $data,
                    [
                        'feedback_id' => 'required|integer|exists:feedback,id',
                        'body' => 'required|string',
                    ]
            );

            if ($validator->fails()) {
                return $this->returnResponseError(422, 'Parameters are not valid.', $validator->errors());
            }

            $comment_data = [
                'user_id' => auth()->user()->id,
                'feedback_id' => $data['feedback_id'],
                'parent_id' => !empty($data['parent_id']) ? $data['parent_id'] : null,
                'body' => $data['body'],
            ];

            $response['comment'] = $this->comment->create($comment_data);
            return $this->returnResponse('Comment added Successfully', $response, 200);

        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->returnResponseError(999, false, 'Something went wrong. Please try again');
        }
    }
}
