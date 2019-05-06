<?php

namespace ViralsLaravel\ImportRelationExcel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Freshbitsweb\Laratables\Laratables;

class BaseExcelController extends Controller
{
    protected $model = '';
    protected $crud = [];


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'crud' => $this->crud,
        ];
        return view('viralslaravelexcel::contents.list', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $entry = $this->model::find($id);
        if ($entry) {
            $entry->delete();
            return response('Delete Suscess', 200);
        }
        return response('Delete error', 422);
    }

    public function process(Request $request)
    {
        return Laratables::recordsOf($this->model, function($query) {
            return $query->orderBy('id', 'desc');
        });
    }
}
