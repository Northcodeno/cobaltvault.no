from django.core.urlresolvers import reverse
from django.http import HttpResponseRedirect


def safeRedirect(request, fallback):
	if request.get_raw_uri() == request.META.get('HTTP_REFERER', '/'):
		return HttpResponseRedirect(reverse(fallback))
	else:
		return HttpResponseRedirect(request.META.get('HTTP_REFERER', '/'))


class MarkdownBlackList(object):
	def __contains__(self, value):
		return value not in ['script']