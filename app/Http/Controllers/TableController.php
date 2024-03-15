<?php

namespace App\Http\Controllers;

use App\Models\Column;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get the tables from the database
        $tables = Table::all();
        //load the columns relation
        $tables->load('columns');
        return response()->json(['data' => $tables, 'success' => true], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        //the data comes from json, parse it to an array
        $data = json_decode($request->getContent());

        $cols = $data->columns;
        $table = $data->table;

        //check if the table already exists
        $tableExists = DB::select("SELECT * FROM information_schema.tables WHERE table_name = '$table'");
        if ($tableExists) {
            return response()->json(['message' => 'Table already exists', 'success' => false], 400);
        }

        $columnsPendentCreate = [];

        //the collumns comes in an json array, parse it to sql cols
        $columns = '';
        foreach ($cols as $column) {
            $columns .= $column->name . ' ' . $column->type . ', ';
            $columnsPendentCreate[] = [
                'name' => $column->name,
                'type' => $column->type,
            ];
        }
        $columns = rtrim($columns, ', ');

        //add created_at and updated_at to the table
        $columns .= ', created_at timestamp, updated_at timestamp';


        $tableCreated = DB::statement("CREATE TABLE $table ($columns)");

        if (!$tableCreated) {
            return response()->json(['message' => 'Table could not be created due an error'], 400);
        }

        //create the table model and save it to the database
        $dbTable = Table::create(['name' => $table]);

        //create the columns and save it to the database
        foreach ($columnsPendentCreate as $column) {
            Column::create([
                'name' => $column['name'],
                'type' => $column['type'],
                'table_id' => $dbTable->id
            ]);
        }

        return response()->json(['message' => 'Table created successfully', 'data' => $dbTable, 'success' => true], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Table $table)
    {
        //show the table and its columns
        $table->load('columns');
        return response()->json(['data' => $table, 'success' => true], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Table $table)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Table $table)
    {
        //the request comes in json
        $data = json_decode($request->getContent());

        //update the table name on the database
        $oldName = $table->name;
        $newName = $data->table;
        $saved = DB::statement("ALTER TABLE $oldName RENAME TO $newName");

        if (!$saved) {
            return response()->json(['message' => 'Table could not be updated due an error'], 400);
        }
        //update the table name on the database
        $table->name = $data->table;
        $table->save();

        return response()->json(['message' => 'Table updated successfully', 'data' => $table, 'success' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        //if the table exists and the user is the owner, delete the table 
        if ($table) {
            $table->delete();
            return response()->json(['message' => 'Table deleted successfully', 'success' => true], 200);
        }
    }
}
