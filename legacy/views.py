from django.shortcuts import render
from django.http import HttpResponsePermanentRedirect
from django.core.urlresolvers import reverse
from django.shortcuts import get_object_or_404

from .models import LegacyProject, LegacyUser

# Create your views here.

def legacy_project(request, project_id, project_action = ""):
	if(project_id.isdigit()):
		project = get_object_or_404(LegacyProject, legacyid=project_id)
	else:
		project = get_object_or_404(LegacyProject, legacyslug=project_id)

	print(project.project.idname)

	return HttpResponsePermanentRedirect(reverse('project', args=[project.project.idname]))

def legacy_register(request):
	
	return HttpResponsePermanentRedirect(reverse('register'))

def legacy_profile(request, user_id):
	if(user_id.isdigit()):
		luser = get_object_or_404(LegacyUser, legacyid=user_id)
		username = luser.user.username
	else:
		username = user_id

	return HttpResponsePermanentRedirect(reverse('profile', args=[username]))
