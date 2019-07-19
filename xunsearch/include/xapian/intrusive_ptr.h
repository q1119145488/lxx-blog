#ifndef XAPIAN_INCLUDED_INTRUSIVE_PTR_H
#define XAPIAN_INCLUDED_INTRUSIVE_PTR_H

//
//  Based on Boost's intrusive_ptr.hpp
//
//  Copyright (c) 2001, 2002 Peter Dimov
//  Copyright (c) 2011,2013,2014,2015 Olly Betts
//
// Distributed under the Boost Software License, Version 1.0.
//
// Boost Software License - Version 1.0 - August 17th, 2003
//
// Permission is hereby granted, free of charge, to any person or organization
// obtaining a copy of the software and accompanying documentation covered by
// this license (the "Software") to use, reproduce, display, distribute,
// execute, and transmit the Software, and to prepare derivative works of the
// Software, and to permit third-parties to whom the Software is furnished to
// do so, all subject to the following:
//
// The copyright notices in the Software and this entire statement, including
// the above license grant, this restriction and the following disclaimer,
// must be included in all copies of the Software, in whole or in part, and
// all derivative works of the Software, unless such copies or derivative
// works are solely in the form of machine-executable object code generated by
// a source language processor.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE, TITLE AND NON-INFRINGEMENT. IN NO EVENT
// SHALL THE COPYRIGHT HOLDERS OR ANYONE DISTRIBUTING THE SOFTWARE BE LIABLE
// FOR ANY DAMAGES OR OTHER LIABILITY, WHETHER IN CONTRACT, TORT OR OTHERWISE,
// ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
// DEALINGS IN THE SOFTWARE.
//
//  See http://www.boost.org/libs/smart_ptr/intrusive_ptr.html for documentation.
//

#if !defined XAPIAN_IN_XAPIAN_H && !defined XAPIAN_LIB_BUILD
# error "Never use <xapian/intrusive_ptr.h> directly; include <xapian.h> instead."
#endif

#include <xapian/visibility.h>

namespace Xapian {
namespace Internal {

/// Base class for objects managed by intrusive_ptr.
class intrusive_base {
    /// Prevent copying.
    intrusive_base(const intrusive_base&);

    /// Prevent assignment.
    void operator=(const intrusive_base&);

  public:
    /** Construct with no references.
     *
     *  The references get added if/when this object is put into an
     *  intrusive_ptr.
     */
    intrusive_base() : _refs(0) { }

    /* There's no need for a virtual destructor here as we never delete a
     * subclass of intrusive_base by calling delete on intrusive_base*.
     */

    /** Reference count.
     *
     *  This needs to be mutable so we can add/remove references through const
     *  pointers.
     */
    mutable unsigned _refs;
};

//
//  intrusive_ptr
//

/// A smart pointer that uses intrusive reference counting.
template<class T> class intrusive_ptr
{
private:

    typedef intrusive_ptr this_type;

public:

    intrusive_ptr(): px( 0 )
    {
    }

    intrusive_ptr( T * p): px( p )
    {
        if( px != 0 ) ++px->_refs;
    }

    template<class U>
    intrusive_ptr( intrusive_ptr<U> const & rhs )
    : px( rhs.get() )
    {
        if( px != 0 ) ++px->_refs;
    }

    intrusive_ptr(intrusive_ptr const & rhs): px( rhs.px )
    {
        if( px != 0 ) ++px->_refs;
    }

    ~intrusive_ptr()
    {
        if( px != 0 && --px->_refs == 0 ) delete px;
    }

#ifdef XAPIAN_MOVE_SEMANTICS
    intrusive_ptr(intrusive_ptr && rhs) : px( rhs.px )
    {
        rhs.px = 0;
    }

    intrusive_ptr & operator=(intrusive_ptr && rhs)
    {
        this_type( static_cast< intrusive_ptr && >( rhs ) ).swap(*this);
        return *this;
    }

    template<class U> friend class intrusive_ptr;

    template<class U>
    intrusive_ptr(intrusive_ptr<U> && rhs) : px( rhs.px )
    {
        rhs.px = 0;
    }

    template<class U>
    intrusive_ptr & operator=(intrusive_ptr<U> && rhs)
    {
        this_type( static_cast< intrusive_ptr<U> && >( rhs ) ).swap(*this);
        return *this;
    }
#endif

    intrusive_ptr & operator=(intrusive_ptr const & rhs)
    {
        this_type(rhs).swap(*this);
        return *this;
    }

    intrusive_ptr & operator=(T * rhs)
    {
        this_type(rhs).swap(*this);
        return *this;
    }

    T * get() const
    {
        return px;
    }

    T & operator*() const
    {
        return *px;
    }

    T * operator->() const
    {
        return px;
    }

    void swap(intrusive_ptr & rhs)
    {
        T * tmp = px;
        px = rhs.px;
        rhs.px = tmp;
    }

private:

    T * px;
};

template<class T, class U> inline bool operator==(intrusive_ptr<T> const & a, intrusive_ptr<U> const & b)
{
    return a.get() == b.get();
}

template<class T, class U> inline bool operator!=(intrusive_ptr<T> const & a, intrusive_ptr<U> const & b)
{
    return a.get() != b.get();
}

template<class T, class U> inline bool operator==(intrusive_ptr<T> const & a, U * b)
{
    return a.get() == b;
}

template<class T, class U> inline bool operator!=(intrusive_ptr<T> const & a, U * b)
{
    return a.get() != b;
}

template<class T, class U> inline bool operator==(T * a, intrusive_ptr<U> const & b)
{
    return a == b.get();
}

template<class T, class U> inline bool operator!=(T * a, intrusive_ptr<U> const & b)
{
    return a != b.get();
}

/// Base class for objects managed by opt_intrusive_ptr.
class XAPIAN_VISIBILITY_DEFAULT opt_intrusive_base {
  public:
    opt_intrusive_base(const opt_intrusive_base&) : _refs(0) { }

    opt_intrusive_base& operator=(const opt_intrusive_base&) {
	// Don't touch _refs.
	return *this;
    }

    /** Construct object which is initially not reference counted.
     *
     *  The reference counting starts if release() is called.
     */
    opt_intrusive_base() : _refs(0) { }

    /* Subclasses of opt_intrusive_base may be deleted by calling delete on a
     * pointer to opt_intrusive_base.
     */
    virtual ~opt_intrusive_base() { }

    void ref() const {
	if (_refs == 0)
	    _refs = 2;
	else
	    ++_refs;
    }

    void unref() const {
	if (--_refs == 1)
	    delete this;
    }

    /** Reference count.
     *
     *  This needs to be mutable so we can add/remove references through const
     *  pointers.
     */
    mutable unsigned _refs;

  protected:
    /** Start reference counting.
     *
     *  The object is constructed with _refs set to 0, meaning it isn't being
     *  reference counted.
     *
     *  Calling release() sets _refs to 1 if it is 0, and from then
     *  opt_intrusive_ptr will increment and decrement _refs.  If it is
     *  decremented to 1, the object is deleted.
     */
    void release() const {
	if (_refs == 0)
	    _refs = 1;
    }
};

//
//  opt_intrusive_ptr
//

/// A smart pointer that optionally uses intrusive reference counting.
template<class T> class opt_intrusive_ptr
{
private:

    typedef opt_intrusive_ptr this_type;

public:

    opt_intrusive_ptr(): px( 0 ), counting( false )
    {
    }

    opt_intrusive_ptr( T * p): px( p ), counting( px != 0 && px->_refs )
    {
	if( counting ) ++px->_refs;
    }

    template<class U>
    opt_intrusive_ptr( opt_intrusive_ptr<U> const & rhs )
    : px( rhs.get() ), counting( rhs.counting )
    {
	if( counting ) ++px->_refs;
    }

    opt_intrusive_ptr(opt_intrusive_ptr const & rhs)
    : px( rhs.px ), counting( rhs.counting )
    {
	if( counting ) ++px->_refs;
    }

    ~opt_intrusive_ptr()
    {
	if( counting && --px->_refs == 1 ) delete px;
    }

#ifdef XAPIAN_MOVE_SEMANTICS
    opt_intrusive_ptr(opt_intrusive_ptr && rhs)
    : px( rhs.px ), counting( rhs.counting )
    {
        rhs.px = 0;
	rhs.counting = 0;
    }

    opt_intrusive_ptr & operator=(opt_intrusive_ptr && rhs)
    {
        this_type( static_cast< opt_intrusive_ptr && >( rhs ) ).swap(*this);
        return *this;
    }

    template<class U> friend class opt_intrusive_ptr;

    template<class U>
    opt_intrusive_ptr(opt_intrusive_ptr<U> && rhs)
    : px( rhs.px ), counting( rhs.counting )
    {
        rhs.px = 0;
	rhs.counting = 0;
    }

    template<class U>
    opt_intrusive_ptr & operator=(opt_intrusive_ptr<U> && rhs)
    {
        this_type( static_cast< opt_intrusive_ptr<U> && >( rhs ) ).swap(*this);
        return *this;
    }
#endif

    opt_intrusive_ptr & operator=(opt_intrusive_ptr const & rhs)
    {
	this_type(rhs).swap(*this);
	return *this;
    }

    opt_intrusive_ptr & operator=(T * rhs)
    {
	this_type(rhs).swap(*this);
	return *this;
    }

    T * get() const
    {
	return px;
    }

    T & operator*() const
    {
	return *px;
    }

    T * operator->() const
    {
	return px;
    }

    void swap(opt_intrusive_ptr & rhs)
    {
	T * tmp = px;
	px = rhs.px;
	rhs.px = tmp;
	bool tmp2 = counting;
	counting = rhs.counting;
	rhs.counting = tmp2;
    }

private:

    T * px;

    bool counting;
};

template<class T, class U> inline bool operator==(opt_intrusive_ptr<T> const & a, opt_intrusive_ptr<U> const & b)
{
    return a.get() == b.get();
}

template<class T, class U> inline bool operator!=(opt_intrusive_ptr<T> const & a, opt_intrusive_ptr<U> const & b)
{
    return a.get() != b.get();
}

template<class T, class U> inline bool operator==(opt_intrusive_ptr<T> const & a, U * b)
{
    return a.get() == b;
}

template<class T, class U> inline bool operator!=(opt_intrusive_ptr<T> const & a, U * b)
{
    return a.get() != b;
}

template<class T, class U> inline bool operator==(T * a, opt_intrusive_ptr<U> const & b)
{
    return a == b.get();
}

template<class T, class U> inline bool operator!=(T * a, opt_intrusive_ptr<U> const & b)
{
    return a != b.get();
}

}
}

#endif // XAPIAN_INCLUDED_INTRUSIVE_PTR_H
