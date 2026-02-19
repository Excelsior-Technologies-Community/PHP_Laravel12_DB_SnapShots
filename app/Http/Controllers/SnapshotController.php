<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\DbSnapshots\Snapshot;
use Spatie\DbSnapshots\SnapshotFactory;
use Spatie\DbSnapshots\SnapshotRepository;

class SnapshotController extends Controller
{
    protected $snapshotFactory;
    protected $snapshotRepository;

    public function __construct(
        SnapshotFactory $snapshotFactory,
        SnapshotRepository $snapshotRepository
    ) {
        $this->snapshotFactory = $snapshotFactory;
        $this->snapshotRepository = $snapshotRepository;
    }

    public function index()
    {
        $snapshots = $this->snapshotRepository->getAll();
        $posts = Post::latest()->paginate(10);
        
        return view('snapshots.index', compact('snapshots', 'posts'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'compress' => 'boolean',
            'connection' => 'nullable|string'
        ]);

        try {
            $this->snapshotFactory->create(
                $request->name,
                $request->connection ?? config('database.default'),
                $request->has('compress') ? $request->compress : true
            );

            return redirect()->route('snapshots.index')
                ->with('success', "Snapshot '{$request->name}' created successfully!");
        } catch (\Exception $e) {
            return redirect()->route('snapshots.index')
                ->with('error', "Failed to create snapshot: " . $e->getMessage());
        }
    }

    public function load($snapshotName)
    {
        try {
            $snapshot = $this->snapshotRepository->findByName($snapshotName);
            
            if (!$snapshot) {
                return redirect()->route('snapshots.index')
                    ->with('error', "Snapshot '{$snapshotName}' not found!");
            }

            $snapshot->load();
            
            return redirect()->route('snapshots.index')
                ->with('success', "Snapshot '{$snapshotName}' loaded successfully!");
        } catch (\Exception $e) {
            return redirect()->route('snapshots.index')
                ->with('error', "Failed to load snapshot: " . $e->getMessage());
        }
    }

    public function delete($snapshotName)
    {
        try {
            $snapshot = $this->snapshotRepository->findByName($snapshotName);
            
            if (!$snapshot) {
                return redirect()->route('snapshots.index')
                    ->with('error', "Snapshot '{$snapshotName}' not found!");
            }

            $snapshot->delete();
            
            return redirect()->route('snapshots.index')
                ->with('success', "Snapshot '{$snapshotName}' deleted successfully!");
        } catch (\Exception $e) {
            return redirect()->route('snapshots.index')
                ->with('error', "Failed to delete snapshot: " . $e->getMessage());
        }
    }

    public function download($snapshotName)
    {
        try {
            $snapshot = $this->snapshotRepository->findByName($snapshotName);
            
            if (!$snapshot) {
                return redirect()->route('snapshots.index')
                    ->with('error', "Snapshot '{$snapshotName}' not found!");
            }

            return response()->download($snapshot->path);
        } catch (\Exception $e) {
            return redirect()->route('snapshots.index')
                ->with('error', "Failed to download snapshot: " . $e->getMessage());
        }
    }

    public function compare(Request $request)
    {
        $request->validate([
            'snapshot1' => 'required|string',
            'snapshot2' => 'required|string'
        ]);

        try {
            $snapshot1 = $this->snapshotRepository->findByName($request->snapshot1);
            $snapshot2 = $this->snapshotRepository->findByName($request->snapshot2);

            if (!$snapshot1 || !$snapshot2) {
                return redirect()->route('snapshots.index')
                    ->with('error', "One or both snapshots not found!");
            }

            // Simple comparison logic (you might want to implement more sophisticated comparison)
            $info1 = $this->getSnapshotInfo($snapshot1);
            $info2 = $this->getSnapshotInfo($snapshot2);

            return view('snapshots.compare', compact('snapshot1', 'snapshot2', 'info1', 'info2'));
        } catch (\Exception $e) {
            return redirect()->route('snapshots.index')
                ->with('error', "Failed to compare snapshots: " . $e->getMessage());
        }
    }

    protected function getSnapshotInfo(Snapshot $snapshot)
    {
        return [
            'name' => $snapshot->name,
            'size' => $this->formatBytes($snapshot->size),
            'created_at' => $snapshot->createdAt,
            'connection' => $snapshot->connectionName,
            'path' => $snapshot->path
        ];
    }

    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}