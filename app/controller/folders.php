<?php

namespace Controller;

class Folders extends Base {

	protected $response;

	public function add( $f3, $params ) {
		if( $f3->exists( 'POST.name' ) ) {
			$folder = new \Model\Folder();
			$folder->reset();
			$folder->name = $f3->get( 'POST.name' );
			$f3->logger->write( "POST parent_id exists: ".$f3->get( 'POST.parent_id' ) );
			if( $f3->exists( 'POST.parent_id' ) && is_numeric( $f3->get( 'POST.parent_id' ) ) ) {
				$folder->parent_id = $f3->get( 'POST.parent_id' );
			}
			if( $folder->save() ) {
				$f3->push( "SESSION.messages", array( $f3->get( 'L.foldercreatesuccessful' ), 0 ) );
			} else {
				$f3->push( "SESSION.messages", array( $f3->get( 'L.foldercreateerror' ), 1 ) );
			}
			$f3->reroute( '/dashboard' );
		} else {
			$f3->set( 'content', 'newfolder.html' );
		}
	}

	public function delete( $f3, $params ) {
		if( $f3->exists( 'POST.id' ) && is_int( $f3->get( 'POST.id' ) ) ) {
			$folder = new \Model\Folder( $params['id'] );
			$folder->delete();
			$f3->SESSION->messages[] = array( "Folder was successfully deleted", 0 );
			$f3->reroute( '/dashboard' );
		} else {
			$f3->SESSION->messages[] = array( "Could not delete folder: No valid folder id given", 1 );
			$f3->reroute( '/dashboard' );
		}
	}

        public function show( \Base $f3, $params ) {
          $f3->set( 'folders', \TreeBuilder::instance()->generateTree() );
          $f3->set( 'content', 'overview.html' );
        }
}

