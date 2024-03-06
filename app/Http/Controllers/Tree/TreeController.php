<?php

namespace App\Http\Controllers\Tree;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TreeController extends Controller
{

    public function buildMerkleTreeConcatenated($data)
    {
        $tree = $data;

        while (count($tree) > 1) {
            $level = [];

            // Process pairs of nodes at the current level
            for ($i = 0; $i < count($tree); $i += 2) {
                $node1 = $tree[$i];
                $node2 = isset($tree[$i + 1]) ? $tree[$i + 1] : ''; // In case the number of nodes is odd

                // Combine the pair of nodes
                $level[] = $node1 . $node2;
            }

            // Update the tree with the new level of nodes
            $tree = $level;
        }

        // Return the root value of the Merkle tree
        return $tree[0];
    }

    public function index()
    {
        $letters = range('a', 'z');
        // default size of the tree
        $treeSize = 4;
        // Slice the array to get the first four letters
        $data = array_slice($letters, 0, $treeSize);

        $rootValue = $this->buildMerkleTreeConcatenated($data);
        return view('tree', compact('data', 'rootValue'));
    }
    public function genrateTree(Request $request)
    {
        $letters = range('a', 'z');

        $treeSize = $request->treeNumber;

        $data = array_slice($letters, 0, $treeSize);

        $rootValue = $this->buildMerkleTreeConcatenated($data);
        return view('tree', compact('data', 'rootValue'));
    }
}
