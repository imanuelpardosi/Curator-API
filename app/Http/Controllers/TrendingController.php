<?php
namespace App\Http\Controllers;

use App\Repositories\TrendingRepository;
use App\Repositories\ImageRepository;
use Illuminate\Http\Request;

class TrendingController extends Controller
{
    protected $trending;
    protected $request;
    protected $image_repository;

    public function __construct(TrendingRepository $trending, Request $request, ImageRepository $image_repository)
    {
        $this->trending = $trending;
        $this->request = $request;
        $this->image_repository=$image_repository;
    }

    public function getList()
    {
        $data = $this->trending->getList();
        $response = [];
        foreach ($data as $key) {
            $response[] = [
                'id' => $key->id,
                'title' => $key->title,
                'keywords' => $key->keywords,
                'use_twitter' => (bool)$key->use_twitter,
                'twitter_whitelist' => $key->twitter_whitelist,
                'position' => $key->position,
                'image' => $key->image,
                'start_date' => strtotime($key->start_date),
                'end_date' => strtotime($key->end_date)
            ];
        }

        return \Response::json(array(
            'error' => false,
            'message'=>'Get tranding success',
            'data' => $response),
            200
        );
    }

    protected function cleanPost($post)
    {
        $post['title'] = trim($post['title']);
        $post['keywords'] = trim($post['keywords']);
        $post['start_date'] = new \DateTime(trim($post['start_date']));
        $post['start_date'] = $post['start_date']->format('Y-m-d') . ' 00:00:00';
        $post['end_date'] = new \DateTime(trim($post['end_date']));
        $post['end_date'] = $post['end_date']->format('Y-m-d') . ' 23:59:59';

        if (isset($post['twitter_whitelist'])) {
            $post['twitter_whitelist'] = trim($post['twitter_whitelist']);
        }
        return $post;

    }

    public function create(Request $request)
    {
        $post = $request->all();

        $validate = $this->validation($post);

        if ($validate !== true) {
            return response()->json(['error' => $validate], 422);

        }
        $post['image'] = $this->image_repository->upload($request->input('image'));

        if (count($this->trending->getList()) > 0) {
            $last_position = $this->trending->getList()->last()->position;
            $post['position'] = $last_position + 1;
        } else {
            $post['position'] = 1;
        }

        $trending = $this->trending->create($this->cleanPost($post));

        if (!$trending) {
            return response()->json(['error' => 'Trending  error  while create'], 500);

        }
        return response()->json(['success' => 'Trending  ' . $trending->title . ' has been created'], 200);

    }

    public function update($id, Request $request)
    {
        try {
            $data['trending'] = $this->trending->findById($id);
            $post['end_date'] = date('d-m-Y', strtotime($data['trending']->end_date));
            $post['start_date'] = date('d-m-Y', strtotime($data['trending']->start_date));
            $post = $request->all();

            $validate = $this->validation($post, $id);

            if ($validate !== true) {
                return response()->json(['error' => $validate], 422);

            }
            $post['image'] = $this->image_repository->upload($request->input('image'));

            $trending = $this->trending->update($this->trending->findById($id), $this->cleanPost($post));

            if (!$trending) {
                return response()->json(['error' => 'Error while updating trending  ' . $trending->title], 500);

            }
            return response()->json(['success' => 'Success update trending ' . $trending->title], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Trending Not Found'], 404);

        }
    }

    public function destroy($id)
    {
        try {
            $trending = $this->trending->findById($id);
            $delete = $this->trending->delete($trending);

            if ($delete) {
                return response()->json(['success' => 'Trending' . $trending->title . ' has been deleted'], 200);
            }
            return response()->json(['error' => 'Trending error to delete'], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Trending Not Found'], 404);

        }
    }

    public function validation($request)
    {
        $rules = [
            'title' => 'required|max:255',
            'keywords' => 'required',
            'start_date' => 'required|date_format:d-m-Y',
            'end_date' => 'required|date_format:d-m-Y'
        ];

        $validator = \Validator::make($request, $rules);

        if ($validator->fails()) {
            return $validator->messages();

        }
        return true;

    }

    public function changePosition(Request $request)
    {
        $data = $request->input('data');
        foreach ($data as $row) {
            $trending = $this->trending->updatePosition($row['id'], $row['position']);
            if (!$trending) {
                return response()->json(['error' => 'Fail while update position'], 500);
                break;
            }
        }
        return response()->json(['success' => 'Trending position has been updated'], 200);
    }
}
