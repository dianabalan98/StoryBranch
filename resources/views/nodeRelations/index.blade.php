@extends('layouts.app')

@section('stylesheets')
<style>
	td {
		width: 300px;
	}
</style>
@endsection

@section('content')
<div class="container">

	@include('partials.storyManagerMenu')

	<br>
	<p>Add a new relation between chapters:</p>

	@if(session('success'))

	    <div class="alert alert-success" role="alert">
	        {{ session('success') }}
	    </div>
	@elseif(session('danger'))

	    <div class="alert alert-danger" role="alert">
	        {{ session('danger') }}
	    </div>
	@endif

	
	<!--BUTON DE ADAUGARE RELATIE-->
	<button id="btn-add" name="btn-add" class="btn addNewContentBtn text-center">Add New Relation</button>

	<br><br><br>

	<!--LIST OF RELATIONS FOR THIS STORY-->
	<div class="row table-responsive text-center">
	    <table class="table" id="tableRelations">
	        <thead>
	            <tr>
	                <th class="text-center">Parent fragment</th>
	                <th class="text-center">Choice</th>
	                <th class="text-center">Child fragment</th>
	                <th class="text-center">Actions</th>
	            </tr>
	        </thead>

	        <tbody id="relations-list" name="relations-list">
		        @foreach($relations as $rel)
		        	<tr id="rel{{$rel['id']}}">

				        <td class="text-center">{{$rel['parent_subtitle']}}</td>
				        <td class="text-center">{{$rel['choice']}}</td>
				        <td class="text-center">{{$rel['child_subtitle']}}</td>


				        <td class="text-center">
				        	<!--BUTOANE DE EDIT SI DELETE-->
				        	<button class="btn btn-warning open-modal icon-edit btnRelation editRel" value="{{$rel['id']}}">
		                	</button>
			                <button class="delete-modal btn btn-danger delete-relation btnRelation deleteRel" value="{{$rel['id']}}">
			                	<b>X</b>
			                </button>
		            	</td>

			        </tr>
		        @endforeach
	        </tbody>
	    </table>
	</div>


	<!-- MODAL -->
	<div class="modal fade" id="relationEditorModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="relationEditorModalLabel">Relation Editor</h4>
                </div>
                <div class="modal-body">

                    <form id="modalFormData" name="modalFormData" class="form-horizontal" novalidate="">

                    	<div id="divParentId" class="form-group">
				    		<label for="parentId">Parent fragment</label>
				            <select class="form-control relMember" id="parentId" name="parentId">
				                @foreach($nodes as $node)

				                    <option value="{{ $node->id }}">{{ $node->subtitle }}</option>

				                @endforeach
				            </select>
				            
				            
				            
				    	</div>

				    	<div id="divChoice" class="form-group">
				    		<label for="choice">Choice:</label>
				        	<input type="text" class="form-control @error('choice') is-invalid @enderror" id="choice" name="choice" required value="{{ old('choice') }}">
				    	</div>
				        
				    	<div id="divChildId" class="form-group">
				    		<label for="childId">Child fragment</label>
				            <select class="form-control relMember" id="childId" name="childId">

				                @foreach($nodes as $node)

				                    <option value="{{ $node->id }}">{{ $node->subtitle }}</option>

				                @endforeach
				            </select>
				    	</div> 
                       
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn storeEditBtn" id="btn-save" value="add">Save changes
                    </button>
                    <input type="hidden" id="relation_id" name="relation_id" value="0">
                </div>
            </div>
        </div>
    </div>

</div>
@endsection


@section('scripts')

    <script src="{{ asset('js/relationCRUD.js') }}"></script>
    <script src="{{ asset('js/validations/relationValidations.js') }}"></script>

@endsection