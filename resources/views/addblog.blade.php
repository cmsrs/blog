<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


<x-app-layout>    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{url('dashboard')}}">{{ __('Dashboard') }}</a> -> {{ __('Add blog post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="container mt-4">

                        @if (Session::has('status'))
                            <div class="mt-2 mb-2 alert alert-danger">
                                {{ Session::get('status') }}
                            </div>
                        @endif                        

                        <div class="card">
                            <div class="card-header text-center font-weight-bold">
                            {{ __('Add Blog Post') }}
                            </div>
                            <div class="card-body">
                                <form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{route('dashboard.blog.store')}}">
                                @csrf
                                    <div class="form-group">
                                    <label for="InputTitle">{{ __('Title') }}</label>
                                    <input type="text" id="title" name="title" class="form-control" required="">
                                    </div>
                                    <div class="form-group">
                                    <label for="InputDescription">{{ __('Description') }}</label>
                                    <textarea name="description" class="form-control" required=""></textarea>
                                    </div>

                                    <div class="form-group">  
                                        <label for="InputDate">{{ __('Publication Date') }}</label>
                                        <input type="date" name="publication_date"  required >  
                                    </div>

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
.btn-primary {
	color: #fff;
	background-color: #007bff;
	border-color: #007bff;
}
.input-group-append {
  cursor: pointer;
}
</style>
