<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BreakingNews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    /**
     * Sync storage files to root storage folder for cPanel compatibility
     * cPanel doesn't support symlinks properly, so we copy files directly
     */
    private function syncStorageFile($relativePath)
    {
        $sourcePath = storage_path('app/public/' . $relativePath);
        $destPath = base_path('storage/' . $relativePath);
        
        // Create directory if not exists
        $destDir = dirname($destPath);
        if (!File::exists($destDir)) {
            File::makeDirectory($destDir, 0755, true);
        }
        
        // Copy file
        if (File::exists($sourcePath)) {
            File::copy($sourcePath, $destPath);
            chmod($destPath, 0755);
        }
    }
    
    /**
     * Delete file from both storage locations
     */
    private function deleteStorageFile($relativePath)
    {
        // Delete from standard Laravel storage
        Storage::disk('public')->delete($relativePath);
        
        // Also delete from root storage folder
        $destPath = base_path('storage/' . $relativePath);
        if (File::exists($destPath)) {
            File::delete($destPath);
        }
    }

    public function banners()
    {
        $banners = Banner::orderBy('order')->get();
        return view('admin.banners', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'title_bn' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'subtitle_bn' => 'nullable|string|max:500',
            'subtitle_en' => 'nullable|string|max:500',
            'image' => 'required|image|max:5000',
            'link' => 'nullable|url',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
            
            // Sync to root storage folder for cPanel
            $this->syncStorageFile($imagePath);
        }

        Banner::create([
            'title_bn' => $request->title_bn,
            'title_en' => $request->title_en,
            'subtitle_bn' => $request->subtitle_bn,
            'subtitle_en' => $request->subtitle_en,
            'image' => $imagePath,
            'link' => $request->link,
            'order' => Banner::max('order') + 1,
            'is_active' => true,
        ]);

        return redirect()->route('admin.banners')->with('success', 'ব্যানার সফলভাবে যোগ করা হয়েছে!');
    }

    public function toggleBanner(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);
        return redirect()->route('admin.banners')->with('success', 'ব্যানার স্ট্যাটাস আপডেট হয়েছে!');
    }

    public function deleteBanner(Banner $banner)
    {
        if ($banner->image) {
            $this->deleteStorageFile($banner->image);
        }
        $banner->delete();
        return redirect()->route('admin.banners')->with('success', 'ব্যানার মুছে ফেলা হয়েছে!');
    }

    public function breakingNews()
    {
        $breakingNews = BreakingNews::orderBy('order')->get();
        return view('admin.breaking-news', compact('breakingNews'));
    }

    public function storeBreakingNews(Request $request)
    {
        $request->validate([
            'content_bn' => 'required|string|max:1000',
            'content_en' => 'nullable|string|max:1000',
        ]);

        BreakingNews::create([
            'content_bn' => $request->content_bn,
            'content_en' => $request->content_en,
            'order' => BreakingNews::max('order') + 1,
            'is_active' => true,
        ]);

        return redirect()->route('admin.breaking-news')->with('success', 'ব্রেকিং নিউজ যোগ করা হয়েছে!');
    }

    public function toggleBreakingNews(BreakingNews $news)
    {
        $news->update(['is_active' => !$news->is_active]);
        return redirect()->route('admin.breaking-news')->with('success', 'স্ট্যাটাস আপডেট হয়েছে!');
    }

    public function deleteBreakingNews(BreakingNews $news)
    {
        $news->delete();
        return redirect()->route('admin.breaking-news')->with('success', 'ব্রেকিং নিউজ মুছে ফেলা হয়েছে!');
    }
}
