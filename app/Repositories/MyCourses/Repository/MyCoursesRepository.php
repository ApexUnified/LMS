<?php

namespace App\Repositories\MyCourses\Repository;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Repositories\CoursePlayer\Interface\CoursePlayerRepositoryInterface;
use App\Repositories\MyCourses\Interface\MyCourseRepositoryInterface;

class MyCoursesRepository implements MyCourseRepositoryInterface
{
    public function __construct(
        private Course $course,
        private Enrollment $enrollment,
        private User $user,
        private CoursePlayerRepositoryInterface $course_player
    ) {}

    public function getMyCourses(string $user_id)
    {
        $enrolled_course_ids = $this->enrollment->where('user_id', $user_id)->pluck('course_id');

        $user = $this->user->find($user_id);

        $courses = $this->course
            ->when($user->hasRole('Student'), function ($query) use ($enrolled_course_ids) {
                $query->whereIn('id', $enrolled_course_ids)
                    ->where('is_published', true)
                    ->where('is_approved', true);
            })
            ->with('instructor')
            ->paginate(6);

        $courses->getCollection()->transform(function ($course) {
            $course->course_progress = $this->course_player->getCourseProgress($course->id);

            return $course;
        });

        return $courses;
    }
}
