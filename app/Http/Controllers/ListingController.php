<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    //show all listings
    public function index()
    {
        return view('listings.index', [
            'heading' => 'Latest Listings',
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(4)
        ]);
    }

    //show single Listing
    public function show(Listing $listing)
    {
        return view('listings.show',[
            'listing' => $listing
        ]);
    }

    //show create a Listing
    public function create()
    {
        return view('listings.create');
    }

    //store a Listing data
    public function store(Request $request)
    {
        $from_fields = $request->validate([
            'title' => 'required',
            'company' => ['required' , Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if($request->hasFile('logo'))
        {
            $from_fields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $from_fields['user_id'] = auth()->id();

        Listing::create($from_fields);

        return redirect('/')->with('message', 'Listing created successfully');
    }

    //edit Listing
    public function edit(Listing $listing)
    {   
        return view('listings.edit', [
            'listing' => $listing
        ]);
    }

    //update Listing data
    public function update(Request $request, Listing $listing)
    {   
        //confirm User is owner

        if($listing->user_id != auth()->id())
        {
            abort(403, 'Unauthorized Action');
        }


        $from_fields = $request->validate([
            'title' => 'required',
            'company' => 'required',
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if($request->hasFile('logo'))
        {
            $from_fields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($from_fields);

        return back()->with('message', 'Listing updated successfully');
    }


    //delete Listing
    public function delete(Listing $listing)
    {

        if($listing->user_id != auth()->id())
        {
            abort(403, 'Unauthorized Action');
        }

        $listing->delete();

        return redirect('/')->with('message', 'Listing deleted successfully');
    }

    //show manage listings
    public function manage()
    {
        return view('listings.manage',[
            'listings' => auth()->user()->listings()->get()
        ]);
    }

}
