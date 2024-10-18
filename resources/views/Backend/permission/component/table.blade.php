<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" name="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên quyền</th>
            <th>Canonical</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($permissions) && is_object($permissions))
        @foreach($permissions as $permission)
        <tr class="rowdel-{{ $permission->id }}">
            <td>
                <input type="checkbox" value="{{ $permission->id }}" name="" class="input-checkbox checkBoxItem">
            </td>
            
            <td>
                <div class="info-item name">{{ $permission->name }}</div>
            </td>
            <td>
                <div class="info-item email">{{ $permission->canonical }}</div>
            </td>
           
            <td class="text-center">
                <a href="{{ route('permission.edit', $permission->encrypted_id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                <a href="{{ route('permission.destroy', $permission->encrypted_id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{ $permissions->links('pagination::bootstrap-4') }}