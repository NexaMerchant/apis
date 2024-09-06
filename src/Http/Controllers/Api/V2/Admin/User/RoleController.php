<?php
namespace NexaMerchant\Apis\Http\Controllers\Api\V2\Admin\User;

use NexaMerchant\Apis\Models\Role;
use NexaMerchant\Apis\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Lauthz\Facades\Enforcer;
use stdClass;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'required|integer|min:1',
            'pageSize' => 'required|integer',
            'name' => 'nullable|string',
            'value' => 'nullable|string',
            'status' => 'nullable|integer',
        ]);
        if ($validator->fails())
        {
            return $this->fails($validator->errors());
        }
        $query = Role::query();
        if ($request->filled('name'))
        {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('value'))
        {
            $query->where('value', 'like', '%' . $request->value . '%');
        }
        if ($request->filled('status'))
        {
            $query->where('status', $request->status);
        }
        $result = new stdClass();
        $result->total = $query->count();
        $roles = $query->offset(($request->page - 1) * $request->pageSize)
            ->limit($request->pageSize)
            ->get([
                'id',
                'name',
                'value',
                'desc',
                'status',
                'created_at',
            ]);
        foreach ($roles as $role)
        {
            $permissions = Enforcer::getPermissionsForUser($role->value);
            $p_ids = Permission::whereIn('permission', array_column($permissions, 2))->pluck('id');
            $role->permissions = $p_ids;
        }
        $result->items = $roles;
        return $this->success('success', $result);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => 'required|string',
            'value' => 'required|string',
            'desc' => 'nullable|string',
            'status' => 'required|integer',
            'permissions' => 'array',
        ]);
        if ($validator->fails())
        {
            return $this->fails($validator->errors());
        }
        $role = Role::find($request->id);
        if (!$role)
        {
            return $this->fails(trans('role.not_found'));
        }
        DB::beginTransaction();
        try
        {
            // 删除角色所有权限
            Enforcer::deletePermissionsForUser($request->value);
            // 添加角色权限
            $permissions = Permission::whereIn('id', $request->permissions)
                ->get([
                'id',
                'permission'
            ]);
            foreach ($permissions as $permission)
            {
                Enforcer::addPermissionForUser($request->value, '', $permission->permission);
            }
            $role->name = $request->name;
            $role->value = $request->value;
            $role->desc = $request->desc;
            $role->status = $request->status;
            $role->save();
            DB::commit();
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return $this->fails(trans('role.update_failed'));
        }
        return $this->success(trans('Success'), []);
    }

    public function setStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'status' => 'required|integer',
        ]);
        if ($validator->fails())
        {
            return $this->fails($validator->errors());
        }
        $role = Role::find($request->id);
        if (!$role)
        {
            return $this->fails(trans('role.not_found'));
        }
        $role->status = $request->status;
        $role->save();
        return $this->success(trans('Success'), []);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'value' => 'required|string',
            'desc' => 'nullable|string',
            'status' => 'required|integer',
        ]);
        if ($validator->fails())
        {
            return $this->fails($validator->errors());
        }
        DB::beginTransaction();
        try
        {
            // 添加角色
            Enforcer::addRoleForUser($request->name, $request->value);
            // 添加角色权限
            $permissions = Permission::whereIn('id', $request->permissions)
                ->get([
                'id',
                'permission'
            ]);
            foreach ($permissions as $permission)
            {
                Enforcer::addPermissionForUser($request->value, '', $permission->permission);
            }
            $role = new Role();
            $role->name = $request->name;
            $role->value = $request->value;
            $role->desc = $request->desc;
            $role->status = $request->status;
            $role->save();
            DB::commit();
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return $this->fails(trans('role.create_failed'));
        }
        
        return $this->success(trans('Success'), []);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);
        if ($validator->fails())
        {
            return $this->fails($validator->errors());
        }
        $role = Role::find($request->id);
        DB::beginTransaction();
        try
        {
            $role->delete();
            Enforcer::deleteRole($role->value);
            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return $this->fails(trans('role.delete_failed'));
        }
        return $this->success(trans('Success'), []);
    }

    public function getRoles()
    {
        $roles = Role::where('status', 1)
            ->get([
                'name',
                'value',
            ]);
        return $this->success(trans('Success'), $roles);
    }
}