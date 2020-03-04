import { Swal, SwalWithoutAnimation } from '../helpers.js'

QUnit.test('stopTimer() method', (assert) => {
  const done = assert.async()

  SwalWithoutAnimation({
    timer: 500
  })

  setTimeout(() => {
    assert.ok(Swal.stopTimer() > 0)
  }, 250)

  setTimeout(() => {
    assert.ok(Swal.isVisible())
    done()
  }, 750)
})

QUnit.test('stopTimer() method called twice', (assert) => {
  const done = assert.async()

  SwalWithoutAnimation({
    timer: 500
  })

  const remainingTime = Swal.stopTimer()

  setTimeout(() => {
    assert.equal(Swal.stopTimer(), remainingTime)
    done()
  }, 100)
})

