#!/bin/bash
#
# pstepw - Upload files/scrots/urls to {{ route('index') }}
# By onodera, modified by TheReverend403

## CONFIGURATION
source ~/.config/pstepw

## FUNCTIONS

# This function sets $file to a selection scrot
selection() {
	uploadme="/tmp/scrot.png"

	maim --hidecursor -s -b 2 -c 1,0,0,0.6 "$uploadme" 2> "/dev/null"
	if [[ "$?" -ge 1 ]]; then
		echo "Selection cancelled."
		exit 1
	fi

	word=selection
}

# This function sets $file to your clipboard contents
clipboard() {
	uploadme="/tmp/scrot.txt"

	xclip -o > "$uploadme"

	word=clipboard
}

# This function sets $file an url
url() {
	type="$(echo "$location" | rev | cut -d "." -f 1 | rev)"
	uploadme="/tmp/url.$type"

	wget --quiet "$location" -O "$uploadme"

	word=url
}

# This function sets $file a file
file() {
	if [[ -f "$location" ]]; then
		uploadme="$location"
	else
		echo "File not found."
		exit 1
	fi

	word=file
}

# This function sets $file to a full screen scrot
desktop() {
	uploadme="/tmp/scrot.png"

	maim --hidecursor "$uploadme"

	word=desktop
}

# This function uploads the $file
upload() {
	url="$(curl --silent -F key="$key" -F file="@$uploadme" "{{ route('api.upload') }}" | grep -o -i "{{ env('UPLOAD_URL') }}/*.[a-z0-9._-]*")"
}

# This function logs the url,  copies the url to the clipboard, and/or opens the url in your browser
feedback() {
	# Copy url to clipboard
	if [[ "$clipboard" == true ]]; then
		echo "$url" | xclip -selection primary
		echo "$url" | xclip -selection clipboard
	fi

	# Log url
	if [[ "$log" == true ]]; then
		echo "$url" >> "$logfile"
	fi

	# Open url in browser
	if [[ "$browser" == true ]]; then
		xdg-open "$url"
	fi

	# Send notification
	if [[ "$notify" == true ]]; then
		notify-send "Upload complete: $url"
	fi

	echo "$url"
}

## EXECUTE

if [[ "$#" -ge 1 ]]; then
	case "$@" in
		-h|--help)
			echo "usage: pstepw [options] [file/url]"
			echo "options:"
			echo "  -h,   --help            print help and exit"
			echo "  -p,   --paste       	upload your clipboard as text"
			echo "  -s,   --selection       upload selection scrot"
			echo "  -v,   --version         print version and exit"
			exit 0
			;;
		-s|--selection)
			selection
			;;
		-v|--version)
			echo "pstepw 0.9.1"
			exit 0
			;;
		-p|--paste)
			clipboard
			;;
		http*)
			location="$@"
			url
			;;
		*)
			location="$@"
			file
			;;
	esac
else
	desktop
fi


if [[ "$#" -eq 0 ]]; then
	desktop
fi

upload
feedback