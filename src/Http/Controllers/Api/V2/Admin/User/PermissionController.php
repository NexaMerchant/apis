<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V2\Admin\User;

use NexaMerchant\Apis\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use stdClass;

class PermissionController extends Controller
{
    /**
     * 
     * permission index
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string',
            'status' => 'nullable|integer',
        ],[
            'title.string' => 'title must be a string',
            'status.integer' => 'status must be an integer',
        ]);
        if ($validator->fails())
        {
            return $this->fails($validator->errors());
        }
        $query = Permission::query();
        if ($request->filled('title'))
        {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        if ($request->filled('status'))
        {
            $query->where('status', $request->status);
        }
        $result = $query->get([
                'id',
                'parent_id',
                'title',
                'name',
                'path',
                'redirect',
                'icon',
                'component',
                'permission',
                'affix',
                'sort',
                'type',
                'status',
                'created_at',
                '_lft',
                '_rgt',
            ])
            ->toTree();
        $this->treeFormat($result);
        return $this->success('success', $result);
    }

    /**
     * 
     * permission get tree
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     */
    public function getTree()
    {
        $all = Permission::where('status', 1)
            ->get([
                'id',
                'title',
                'parent_id',
                'icon',
                '_lft',
                '_rgt',
            ])
            ->toTree();
        $this->treeFormat($all);
        return $this->success('success', $all);
    }

    public function treeFormat($obj)
    {
        foreach ($obj as $v)
        {
            unset($v->_lft);
            unset($v->_rgt);
            if (count($v->children))
            {
                $this->treeFormat($v->children);
            }
        }
    }

    /**
     * 
     * permission update
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     */

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1',
            'parent_id' => 'nullable|integer',
            'title' => 'required|string',
            'name' => 'nullable|string',
            'redirect' => 'nullable|string',
            'icon' => 'nullable|string',
            'path' => 'nullable|string',
            'component' => 'nullable|string',
            'permission' => 'required|string',
            'affix' => 'nullable|integer',
            'sort' => 'nullable|integer',
            'status' => 'nullable|integer',
            'type' => 'required|integer',
        ],[
            'id.required' => 'id is required',
            'id.integer' => 'id must be an integer',
            'id.min' => 'id must be greater than 0',
            'parent_id.integer' => 'parent_id must be an integer',
            'title.required' => 'title is required',
            'title.string' => 'title must be a string',
            'name.string' => 'name must be a string',
            'redirect.string' => 'redirect must be a string',
            'icon.string' => 'icon must be a string',
            'path.string' => 'path must be a string',
            'component.string' => 'component must be a string',
            'permission.required' => 'permission is required',
            'permission.string' => 'permission must be a string',
            'affix.integer' => 'affix must be an integer',
            'sort.integer' => 'sort must be an integer',
            'status.integer' => 'status must be an integer',
            'type.required' => 'type is required',
            'type.integer' => 'type must be an integer',
        ]);
        if ($validator->fails())
        {
            return $this->fails($validator->errors());
        }
        $permission = Permission::where('permission', $request->permission)
            ->where('id', '<>', $request->id)
            ->first();
        if ($permission)
        {
            return $this->fails('permission already exists');
        }
        $permission = Permission::find($request->id);
        if (!$permission)
        {
            return $this->fails('permission not found');
        }
        if ($request->filled('parent_id'))
        {
            $parent = Permission::find($request->parent_id);
            if (!$parent)
            {
                return $this->fails('parent not found');
            }
            $permission->parent_id = $request->parent_id;
        }
        $permission->title = $request->title;
        if ($request->filled('name'))
        {
            $permission->name = $request->name;
        }
        if ($request->filled('redirect'))
        {
            $permission->redirect;
        }
        if ($request->filled('icon'))
        {
            $permission->icon = $request->icon;
        }
        if ($request->filled('path'))
        {
            $permission->path = $request->path;
        }
        if ($request->filled('component'))
        {
            $permission->component = $request->component;
        }
        $permission->permission = $request->permission;
        if ($request->filled('affix'))
        {
            $permission->affix = $request->affix;
        }
        if ($request->filled('sort'))
        {
            $permission->sort = $request->sort;
        }
        if ($request->filled('status'))
        {
            $permission->status = $request->status;
        }
        if ($request->filled('type'))
        {
            $permission->type = $request->type;
        }
        $permission->save();
        return $this->success('success');
    }

    /**
     * 
     * permission create
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     */

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'nullable|integer',
            'title' => 'required|string',
            'name' => 'nullable|string',
            'redirect' => 'nullable|string',
            'icon' => 'nullable|string',
            'component' => 'nullable|string',
            'path' => 'nullable|string',
            'permission' => 'required|string|unique:permissions',
            'affix' => 'nullable|integer',
            'sort' => 'nullable|integer',
            'status' => 'nullable|integer',
            'type' => 'required|integer',
        ],
        [
            'parent_id.integer' => 'parent_id must be an integer',
            'title.required' => 'title is required',
            'title.string' => 'title must be a string',
            'name.string' => 'name must be a string',
            'redirect.string' => 'redirect must be a string',
            'icon.string' => 'icon must be a string',
            'component.string' => 'component must be a string',
            'path.string' => 'path must be a string',
            'permission.required' => 'permission is required',
            'permission.string' => 'permission must be a string',
            'permission.unique' => 'permission already exists',
            'affix.integer' => 'affix must be an integer',
            'sort.integer' => 'sort must be an integer',
            'status.integer' => 'status must be an integer',
            'type.required' => 'type is required',
            'type.integer' => 'type must be an integer',
        ]);
        if ($validator->fails())
        {
            return $this->fails($validator->errors());
        }
        $permission = new Permission();
        if ($request->filled('parent_id') && $request->parent_id!="0")
        {
            $parent = Permission::find($request->parent_id);
            if (!$parent)
            {
                return $this->fails('parent not found');
            }
            $permission->parent_id = $request->parent_id;
        }else{
            $permission->parent_id = 0;
        }
        $permission->title = $request->title;
        if ($request->filled('name'))
        {
            $permission->name = $request->name;
        }
        if ($request->filled('redirect'))
        {
            $permission->redirect;
        }
        if ($request->filled('icon'))
        {
            $permission->icon = $request->icon;
        }
        if ($request->filled('path'))
        {
            $permission->path = $request->path;
        }
        if ($request->filled('component'))
        {
            $permission->component = $request->component;
        }
        $permission->permission = $request->permission;
        if ($request->filled('affix'))
        {
            $permission->affix = $request->affix;
        }
        if ($request->filled('sort'))
        {
            $permission->sort = $request->sort;
        }
        if ($request->filled('status'))
        {
            $permission->status = $request->status;
        }
        if ($request->filled('type'))
        {
            $permission->type = $request->type;
        }
        $permission->save();
        return $this->success('success');
    }

    /**
     * 
     * permission delete
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1',
        ],[
            'id.required' => 'id is required',
            'id.integer' => 'id must be an integer',
            'id.min' => 'id must be greater than 0',
        ]);
        if ($validator->fails())
        {
            return $this->fails($validator->errors());
        }
        $permission = Permission::find($request->id);
        if (!$permission)
        {
            return $this->fails('permission not found');
        }
        $permission->delete();
        return $this->success('success');
    }
}