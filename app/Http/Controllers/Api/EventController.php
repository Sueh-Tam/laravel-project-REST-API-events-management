<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationShips;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use CanLoadRelationShips;
    private array $relations = ['user', 'attendees', 'attendees.user'];

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index()
    {
        //
        $query = Event::query();
        $query = $this->loadRelationShips(Event::query());
        
        //To disable any Relation, just remove it from the array

        $this->shouldIncludeRelation('user');
        return  EventResource::collection(
            $query->latest()->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    protected function shouldIncludeRelation(string $relation): bool{
        
        $include =  request()->query('include');
        if(!$include){
            return false;
        }

        $relations = array_map('trim',explode(',', $include));

        //dd($relations);
        return in_array($relation, $relations);
    }
     public function store(Request $request)
     {
         $event = Event::create([
             ...$request->validate([
                 'name' => 'required|string|max:255',
                 'description' => 'nullable|string',
                 'start_time' => 'required|date',
                 'end_time' => 'required|date|after:start_time'
             ]),
             'user_id' => $request->user()->id
         ]);
 
         return new EventResource($this->loadRelationShips($event));
     }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
        
        return new EventResource(
            $this->loadRelationships($event)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        /*
        if(Gate::denies('update-event',$event)){
           abort(403,'You are not allowed to update this Event.');
        }
        */

        $this->authorize('update-event', $event);

        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time'
            ])
        );
        return new EventResource($this->loadRelationShips($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //

        $event->delete();
        /*
        return response()->json([
            'message' => 'Event deleted successfully'
        ]);*/
        return response(status:204);
    }
}
