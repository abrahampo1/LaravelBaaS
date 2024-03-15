<?php

namespace App\Http\Controllers;

use App\Models\Column;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColumnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //the data comes from json, parse it to an array
        $data = json_decode($request->getContent());

        //create the column on the table on the database first, if all ok, create on the model
        $tableID = $data->table_id;
        $column = $data->name;
        $type = $data->type;

        $table = \App\Models\Table::find($tableID);
        $tableName = $table->name;

        $dbOk = DB::statement("ALTER TABLE $tableName ADD $column $type");
        if (!$dbOk) {
            return response()->json(['message' => 'Error creating column', 'success' => false], 400);
        }

        //create the column
        $column = Column::create([
            'name' => $column,
            'type' => $type,
            'table_id' => $tableID
        ]);

        return response()->json(['message' => 'Column created successfully', 'data' => $column, 'success' => true], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Column $column)
    {
        //show the column
        return response()->json(['data' => $column, 'success' => true], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Column $column)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Column $column)
    {
        //update the collumns on the database first, and then update the column
        $table = $column->table->name;
        $columnName = $column->name;
        $newColumnName = $request->name;

        //if the type is not on the request, use the current type
        $newColumnType = $request->type ? $request->type : $column->type;
        $dbOk = DB::statement("ALTER TABLE $table CHANGE $columnName $newColumnName $newColumnType");
        if (!$dbOk) {
            return response()->json(['message' => 'Error updating column', 'success' => false], 400);
        }
        //update the column
        $column->update([
            'name' => $newColumnName,
            'type' => $newColumnType
        ]);

        //dont load table
        $column->unsetRelation('table');

        return response()->json(['message' => 'Column updated successfully', 'data' => $column, 'success' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Column $column)
    {
        //destroy the column on the database
        $table = $column->table->name;
        $columnName = $column->name;
        DB::statement("ALTER TABLE $table DROP COLUMN $columnName");
        //detroy the column
        $column->delete();

        return response()->json(['message' => 'Column deleted successfully', 'success' => true], 200);
    }
}
