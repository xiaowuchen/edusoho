<?php
/**
 * User: Edusoho V8
 */

namespace WebBundle\Controller;

use Topxia\Service\Common\ServiceKernel;
use Symfony\Component\HttpFoundation\Request;

class CourseManageController extends BaseController
{
	public function tasksAction(Request $request, $id, $courseId)
    {
        $course      = $this->getCourseService()->tryManageCourse($courseId);
        $tasks       = $this->getTaskService()->findUserTasksByCourseId($courseId, $this->getUser()->getId());
        $courseItems = $this->getCourseService()->getCourseItems($courseId);

        return $this->render('WebBundle:TaskManage:list.html.twig', array(
            'tasks'  => $tasks,
            'course' => $course,
            'items'  => $courseItems
        ));
    }

    protected function getTaskService()
    {
        return $this->createService('Task:TaskService');
    }

    protected function getCourseService()
    {
        return $this->createService('Course:CourseService');
    }
}