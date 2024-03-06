<?php

namespace App\Services;

class MerkleTree
{
    protected $hashAlgorithm;
    protected $leafHashes;
    protected $rootHash;

    public function __construct($hashAlgorithm = 'sha256')
    {
        $this->hashAlgorithm = $hashAlgorithm;
        $this->leafHashes = [];
    }

    public function addLeaf($data)
    {
        $this->leafHashes[] = hash($this->hashAlgorithm, $data);
    }

    public function buildTree()
    {
        $hashes = $this->leafHashes;

        while (count($hashes) > 1) {
            $levelHashes = [];
            for ($i = 0; $i < count($hashes); $i += 2) {
                $levelHashes[] = hash($this->hashAlgorithm, $hashes[$i] . (isset($hashes[$i + 1]) ? $hashes[$i + 1] : $hashes[$i]));
            }
            $hashes = $levelHashes;
        }

        $this->rootHash = $hashes[0];
    }

    public function getRootHash()
    {
        return $this->rootHash;
    }

    public function getLeafHashes()
    {
        return $this->leafHashes;
    }

    public function verify($data, $rootHash)
    {
        $hash = hash($this->hashAlgorithm, $data);
        return $hash === $rootHash;
    }
}

// Usage example:

// Create a Merkle tree
$merkleTree = new MerkleTree();
$merkleTree->addLeaf('Data1');
$merkleTree->addLeaf('Data2');
$merkleTree->addLeaf('Data3');
$merkleTree->buildTree();

// Get the root hash
$rootHash = $merkleTree->getRootHash();
echo "Root Hash: $rootHash\n";

// Get the leaf hashes
$leafHashes = $merkleTree->getLeafHashes();
echo "Leaf Hashes:\n";
foreach ($leafHashes as $index => $leafHash) {
    echo "Leaf $index: $leafHash\n";
}
