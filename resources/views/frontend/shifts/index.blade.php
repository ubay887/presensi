@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('shift_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.shifts.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.shift.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.shift.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Shift">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.shift.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.shift.fields.nama_shift') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.shift.fields.checkin') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.shift.fields.checkout') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.shift.fields.smsin') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.shift.fields.smsout') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.shift.fields.is_over_time') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shifts as $key => $shift)
                                    <tr data-entry-id="{{ $shift->id }}">
                                        <td>
                                            {{ $shift->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $shift->nama_shift ?? '' }}
                                        </td>
                                        <td>
                                            {{ $shift->checkin ?? '' }}
                                        </td>
                                        <td>
                                            {{ $shift->checkout ?? '' }}
                                        </td>
                                        <td>
                                            {{ $shift->smsin ?? '' }}
                                        </td>
                                        <td>
                                            {{ $shift->smsout ?? '' }}
                                        </td>
                                        <td>
                                            {{ $shift->is_over_time ?? '' }}
                                        </td>
                                        <td>
                                            @can('shift_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.shifts.show', $shift->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('shift_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.shifts.edit', $shift->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('shift_delete')
                                                <form action="{{ route('frontend.shifts.destroy', $shift->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('shift_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.shifts.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Shift:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection