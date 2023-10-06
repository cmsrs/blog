<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">


<x-app-layout>    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{url('dashboard')}}">{{ __('Dashboard') }}</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="container">

                        <a href="{{route('dashboard.blog.create')}}" class="btn btn-primary mt-2 mb-4">{{ __('Add Blog Post') }}</a>
                        @if($is_admin )
                        <a href="{{route('dashboard.blog.import')}}" class="btn btn-primary ml-4 mt-2 mb-4">{{ __('Import Blogs') }}</a>     
                        @endif                        
                        
                        @if (Session::has('status'))
                            <div class="mt-2 mb-2 alert alert-success">
                                {{ Session::get('status') }}
                            </div>
                        @endif                        

                        @if($blogs->count())
                        <table class="table table-hover">
                            <thead>
                            <tr>                            
                                <th scope="col">{{ __('Title') }}</th>
                                <th scope="col">{{ __('Description') }}</th>
                                <th scope="col">@sortablelink('publication_date', __('Publication Date') )</th>
                            </tr>                            
                            </thead>
                            <tbody>
                            @foreach ($blogs as $blog)
                            <tr>
                                <td>{{ $blog->title }}</td>
                                <td>{{ $blog->description }}</td>
                                <td>{{ $blog->publication_date }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @else
                        <p>You don't have any blog yet.</p>
                        @endif
                    </div>
                    {{ $blogs->links() }}                    


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
