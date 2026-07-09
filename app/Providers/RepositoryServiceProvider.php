<?php

namespace App\Providers;

use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Contracts\EpisodeRepositoryInterface;
use App\Repositories\Contracts\SeasonRepositoryInterface;
use App\Repositories\Contracts\SeriesRepositoryInterface;
use App\Repositories\Contracts\StreamingServiceRepositoryInterface;
use App\Repositories\Contracts\TopicRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\CommentRepository;
use App\Repositories\Eloquent\EpisodeRepository;
use App\Repositories\Eloquent\SeasonRepository;
use App\Repositories\Eloquent\SeriesRepository;
use App\Repositories\Eloquent\StreamingServiceRepository;
use App\Repositories\Eloquent\TopicRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Interface => implementation bindings auto-registered by the framework.
     *
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        SeriesRepositoryInterface::class => SeriesRepository::class,
        SeasonRepositoryInterface::class => SeasonRepository::class,
        EpisodeRepositoryInterface::class => EpisodeRepository::class,
        TopicRepositoryInterface::class => TopicRepository::class,
        CommentRepositoryInterface::class => CommentRepository::class,
        StreamingServiceRepositoryInterface::class => StreamingServiceRepository::class,
        UserRepositoryInterface::class => UserRepository::class,
    ];
}
