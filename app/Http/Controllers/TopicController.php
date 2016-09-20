<?php
namespace App\Http\Controllers;
use Kurio\Common\Repositories\Topic\TopicRepository;
use App\Topic;
use Illuminate\Http\Request;
use Validator;

class TopicController extends Controller
{
    protected $topic;
    protected $request;

    public function __construct(TopicRepository $topic, Request $request)
    {
        $this->topic = $topic;
        $this->request = $request;
    }
    
    public function getList()
    {
        $data['topics'] = $this->topic->getList();
        return response()->json($data,200);
    }

    public function update(Request $request, $id)
    {
        try {
            $topic = $this->topic->findById($id);

            $data['name'] = $request->input('name');
            $data['priority'] = $request->input('priority');

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'priority' => 'required'
            ]);


            if ($validator->fails()) {
                return response()->json(['error' => 'name & priority can not empty'], 200);

            }
            $topic = $this->topic->update($topic, $data);


            if (!$topic) {
                return response()->json(['error' => 'Error while update an topic'], 500);

            }
            return response()->json(['success' => 'Topic ' . $topic->name . ' has been update'], 200);


        } catch (\Exception $e) {
            return response()->json(['error' => 'Topic Not Found '. $e], 404);

        }
    }

    public function destroy($id)
    {
        try {
            $topic = $this->topic->findById($id);
            $delete = $this->topic->delete($topic);

            if ($delete) {
                return response()->json(['success' => 'Topic ' . $topic->name . ' has been deleted'], 200);
            }
            return response()->json(['error' => 'Topic error to delete'], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Topic Not Found'], 404);

        }
    }

    public function create(Request $request)
    {
        $data['name'] = $request->input('name');
        $data['priority'] = $request->input('priority');
        $topic = $this->topic->create($data);

        if ($topic) {
            return response()->json(['success' => 'Topic ' . $topic->name . ' has been submitted'], 200);

        }
        return response()->json(['error' => 'Error while submit an topic'], 500);

    }

    public function index()
    {
        $topic =Topic::find(1);

        return $topic;

    }
}