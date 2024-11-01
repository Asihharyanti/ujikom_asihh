@extends('layout.backend.app',[
    'title' => 'Manage gallery',
    'pageTitle' =>'Manage gallery',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="notify"></div>

<div class="card">
    <div class="card-header">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-modal">
          Tambah Data
        </button>
    </div>
        <div class="card-body">
            <div class="table-responsive">    
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>text</th>
                            <th>foto</th>
                            <th>tanggal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="create-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="create-modalLabel">Create Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="createForm">
        <div class="form-group">
            <label for="n">Name</label>
            <input type="" required="" id="text" name="text" class="form-control">
        </div>
        <div class="form-group">
          <label for="e">Foto</label>
          <input type="file" required id="foto" name="foto" class="form-control">
      </div>
        <div class="form-group">
            <label for="p">tanggal</label>
            <input type="tanggal" required="" id="tanggal" name="tanggal" class="form-control">
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-store">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Create -->

<!-- Modal Edit -->
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="edit-modalLabel">Edit Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editForm">
        <div class="form-group">
            <label for="text">text</label>
            <input type="hidden" required="" id="id" name="id" class="form-control">
            <input type="" required="" id="text" name="text" class="form-control">
        </div>
        <div class="form-group">
          <label for="foto">Foto</label>
          <br>
          <img id="preview-foto" src="" alt="Foto" width="100">
          <input type="file" id="foto" name="foto" class="form-control mt-2">
      </div>
      
        <div class="form-group">
            <label for="tanggal">tanggal</label>
            <input type="" required="" id="tanggal" name="tanggal" class="form-control">
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-update">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Edit -->

<!-- Destroy Modal -->
<div class="modal fade" id="destroy-modal" tabindex="-1" role="dialog" aria-labelledby="destroy-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="destroy-modalLabel">Yakin Hapus ?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger btn-destroy">Hapus</button>
      </div>
    </div>
  </div>
</div>
<!-- Destroy Modal -->

@stop

@push('js')
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>

<script type="text/javascript">

  $(function () {
    
    var table = $('.data-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('gallery.index') }}",
    columns: [
        {data: 'DT_RowIndex', name: 'id'},
        {data: 'text', name: 'text'},
        {
            data: 'foto', 
            name: 'foto',
            render: function(data) {
                return `<img src="/${data}" width="50" height="50">`;
            }
        },
        {data: 'tanggal', name: 'tanggal'},
        {data: 'action', name: 'action', orderable: false, searchable: true},
    ]
});
  });


    // Reset Form
        function resetForm(){
            $("[name='text']").val("")
            $("[name='foto']").val("")
            $("[name='tanggal']").val("")
        }
    // 

      // Create
    $("#createForm").on("submit", function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        
        $.ajax({
            url: "/admin/gallery",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                $("#create-modal").modal("hide");
                $('.data-table').DataTable().ajax.reload();
                flash("success", "Data berhasil ditambah");
                resetForm();
            }
        });
    });

    // Create

    // Edit & Update
    $('body').on("click",".btn-edit",function(){
        var id = $(this).attr("id")
        
        $.ajax({
            url: "/admin/gallery/"+id+"/edit",
            method: "GET",
            success:function(response){
                $("#edit-modal").modal("show")
                $("#id").val(response.id)
                $("#text").val(response.text)
                $("#foto").val(response.foto)
                $("#tanggal").val(response.tanggal)
            }
        })
    });

    $('body').on("click", ".btn-edit", function () {
    var id = $(this).attr("id");

    $.ajax({
        url: "/admin/gallery/" + id + "/edit",
        method: "GET",
        success: function (response) {
            $("#edit-modal").modal("show");
            $("#id").val(response.id);
            $("#text").val(response.text);
            $("#tanggal").val(response.tanggal);
            
            // Gunakan `asset` untuk memastikan path gambar benar
            $("#preview-foto").attr("src", "{{ asset('img') }}/" + response.foto);
        }
    });
});




    //Edit & Update

    $('body').on("click",".btn-delete",function(){
        var id = $(this).attr("id")
        $(".btn-destroy").attr("id",id)
        $("#destroy-modal").modal("show")
    });

    $(".btn-destroy").on("click",function(){
        var id = $(this).attr("id")

        $.ajax({
            url: "/admin/gallery/"+id,
            method: "DELETE",
            success:function(){
                $("#destroy-modal").modal("hide")
                $('.data-table').DataTable().ajax.reload();
                flash('success','Data berhasil dihapus')
            }
        });
    })

    function flash(type,message){
        $(".notify").html(`<div class="alert alert-`+type+` alert-dismissible fade show" role="alert">
                              `+message+`
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>`)
    }

</script>
@endpush