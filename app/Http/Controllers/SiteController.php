<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\LandingPage;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;

class SiteController extends Controller
{
    public function home()
    {
        return view('site.home', [
            'services' => Service::visible()->where('is_featured', true)->take(6)->get(),
            'projects' => Project::visible()->where('is_featured', true)->take(4)->get(),
            'team' => page_on('team') ? TeamMember::visible()->take(4)->get() : collect(),
            'reviews' => Testimonial::visible()->take(9)->get(),
            'posts' => page_on('blog') ? Post::live()->take(3)->get() : collect(),
            'logos' => \App\Models\Logo::visible()->get()->groupBy('group'),
        ]);
    }

    public function about()
    {
        return view('site.about', [
            'reviews' => Testimonial::visible()->take(9)->get(),
            'logos' => \App\Models\Logo::visible()->get()->groupBy('group'),
            'team' => page_on('team') ? TeamMember::visible()->take(4)->get() : collect(),
        ]);
    }

    public function services()
    {
        return view('site.services', ['services' => Service::visible()->get()]);
    }

    public function service(Service $service)
    {
        abort_unless($service->is_visible, 404);

        return view('site.service', [
            'service' => $service,
            'others' => Service::visible()->whereKeyNot($service->id)->take(3)->get(),
            'projects' => Project::visible()->take(2)->get(),
        ]);
    }

    public function work()
    {
        $projects = Project::visible()->get();

        return view('site.work', [
            'projects' => $projects,
            'categories' => $projects->pluck('category')->filter()->unique()->values(),
        ]);
    }

    public function project(Project $project)
    {
        abort_unless($project->is_visible, 404);

        return view('site.project', [
            'project' => $project,
            'more' => Project::visible()->whereKeyNot($project->id)->take(2)->get(),
        ]);
    }

    public function blog()
    {
        abort_unless(page_on('blog'), 404);

        return view('site.blog', ['posts' => Post::live()->paginate(9)]);
    }

    public function post(Post $post)
    {
        abort_unless(page_on('blog') && $post->is_published && $post->published_at <= now(), 404);

        return view('site.post', ['post' => $post, 'recent' => Post::live()->whereKeyNot($post->id)->take(4)->get()]);
    }

    public function faq()
    {
        abort_unless(page_on('faq'), 404);

        return view('site.faq', ['faqs' => Faq::visible()->get()]);
    }

    public function team()
    {
        abort_unless(page_on('team'), 404);

        return view('site.team', ['team' => TeamMember::visible()->get()]);
    }

    public function contact()
    {
        return view('site.contact', ['services' => Service::visible()->pluck('title')]);
    }

    public function landing(LandingPage $landingPage)
    {
        abort_unless($landingPage->is_visible, 404);

        return view($landingPage->template === 'ecommerce' ? 'site.landing-ecommerce' : 'site.landing', [
            'lp' => $landingPage,
            'projects' => $landingPage->show_projects ? Project::visible()->take(4)->get() : collect(),
            'reviews' => $landingPage->show_testimonials ? Testimonial::visible()->take(6)->get() : collect(),
        ]);
    }

    public function privacy()
    {
        return view('site.legal', ['title' => 'Privacy Policy', 'body' => \App\Models\Setting::get('legal.privacy', \App\Support\LegalDefaults::privacy())]);
    }

    public function terms()
    {
        return view('site.legal', ['title' => 'Terms & Conditions', 'body' => \App\Models\Setting::get('legal.terms', \App\Support\LegalDefaults::terms())]);
    }

    public function location(\App\Models\Location $location)
    {
        abort_unless($location->is_visible, 404);

        return view('site.location', [
            'location' => $location,
            'services' => Service::visible()->take(4)->get(),
            'projects' => Project::visible()->take(2)->get(),
        ]);
    }

    /** Any unknown address: follow a redirect from the admin if there is one, otherwise show the 404 page. */
    public function fallback(\Illuminate\Http\Request $request)
    {
        $r = \App\Models\Redirect::where('from', \App\Models\Redirect::normalise($request->path()))->where('is_active', true)->first();
        abort_unless($r, 404);
        $r->increment('hits', 1, ['last_hit_at' => now()]);
        $to = str_starts_with($r->to, 'http') ? $r->to : url($r->to);
        $qs = $request->getQueryString();

        return redirect()->away($to.($qs ? (str_contains($to, '?') ? '&' : '?').$qs : ''), $r->code);
    }

    public function thanks()
    {
        return response()->view('site.thanks')->header('X-Robots-Tag', 'noindex');
    }

    public function sitemap()
    {
        $urls = collect([route('home'), route('about'), route('services'), route('work'), route('contact'), route('privacy'), route('terms')]);
        if (page_on('team')) {
            $urls->push(route('team'));
        }
        if (page_on('faq')) {
            $urls->push(route('faq'));
        }
        $urls = $urls->map(fn ($u) => ['loc' => $u, 'lastmod' => null]);
        Service::visible()->get()->each(fn ($s) => $urls->push(['loc' => route('services.show', $s), 'lastmod' => $s->updated_at]));
        Project::visible()->get()->each(fn ($p) => $urls->push(['loc' => route('work.show', $p), 'lastmod' => $p->updated_at]));
        if (page_on('blog')) {
            $urls->push(['loc' => route('blog'), 'lastmod' => null]);
            Post::live()->get()->each(fn ($p) => $urls->push(['loc' => route('blog.show', $p), 'lastmod' => $p->updated_at]));
        }
        \App\Models\Location::visible()->get()->each(fn ($l) => $urls->push(['loc' => route('location', $l), 'lastmod' => $l->updated_at]));
        LandingPage::where('is_visible', true)->where('noindex', false)->get()
            ->each(fn ($l) => $urls->push(['loc' => route('landing', $l), 'lastmod' => $l->updated_at]));

        return response()->view('site.sitemap', ['urls' => $urls])->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        if (\App\Models\Setting::get('site.noindex')) {
            return response("User-agent: *\nDisallow: /\n")->header('Content-Type', 'text/plain');
        }
        $body = "User-agent: *\nDisallow: /".trim(config('app.admin_path'), '/')."\nDisallow: /thank-you\n\nSitemap: ".route('sitemap')."\n";

        return response($body)->header('Content-Type', 'text/plain');
    }
}
